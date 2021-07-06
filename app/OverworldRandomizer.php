<?php

namespace ALttP;

use ALttP\Contracts\Randomizer as RandomizerContract;
use Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessTimedOutException;

/**
 * Main class for randomization. All the magic happens here. We use mt_rand as it is much faster than rand. Not all PHP
 * functions support mt_rand (e.g. array_shuffle), so those had to be cloned to maintain seed integrity.
 */
class OverworldRandomizer implements RandomizerContract
{
	const LOGIC = -1;
	const VERSION = '0.1.6.0';
	const BRANCH = 'overworld';
	protected $world;
	/** @var array */
	private $entrance_lookup = [
		'none' => 'vanilla',
		'full' => 'full',
		'simple' => 'simple',
		'restricted' => 'restricted',
		'crossed' => 'crossed',
		'insanity' => 'insanity',
		'dungeonssimple' => 'dungeonssimple',
		'dungeonsfull' => 'dungeonsfull',
	];
	/** @var array */
	private $goal_lookup = [
		'ganon' => 'ganon',
		'fast_ganon' => 'crystals',
		'dungeons' => 'dungeons',
		'pedestal' => 'pedestal',
		'triforce-hunt' => 'triforcehunt',
	];
	/** @var array */
	private $swords_lookup = [
		'randomized' => 'random',
		'assured' => 'assured',
		'vanilla' => 'vanilla',
		'swordless' => 'swordless',
		'bombs' => 'bombs',
	];

	/**
	 * Create a new Overworld Randomizer. This currently only works with one
	 * world. So we use the first of the array passed in.
	 *
	 * @param array  $worlds  worlds to randomize
	 *
	 * @return void
	 */
	public function __construct(array $worlds)
	{
		$this->world = reset($worlds);
		$this->world->setBranch(static::BRANCH);

		if (!$this->world instanceof World) {
			throw new \OutOfBoundsException;
		}
	}

	/**
	 * Fill all empty Locations with Items using logic from the World. This is achieved by first setting up base
	 * portions of the world. Then taking the remaining empty locations we order them, and try to fill them in
	 * order in a way that opens more locations.
	 *
	 * @return void
	 */
	public function randomize(): void
	{
		$flags = [];
		if ($this->world->config('dungeonItems') === 'b') {
			$flags = array_merge($flags, [
				'--bigkeyshuffle',
			]);
		} else if ($this->world->config('dungeonItems') === 'mc') {
			$flags = array_merge($flags, [
				'--mapshuffle',
				'--compassshuffle',
			]);
		} elseif ($this->world->config('dungeonItems') === 'mcs') {
			$flags = array_merge($flags, [
				'--mapshuffle',
				'--compassshuffle',
				'--keyshuffle',
			]);
		} elseif ($this->world->config('dungeonItems') === 'full') {
			$flags = array_merge($flags, [
				'--mapshuffle',
				'--compassshuffle',
				'--keyshuffle',
				'--bigkeyshuffle',
			]);
		}

		$mode = 'standard';
		if ($this->world instanceof World\Open) {
			$mode = 'open';
		} elseif ($this->world instanceof World\Inverted) {
			$mode = 'inverted';
		}
		if ($this->world instanceof World\Retro) {
			$mode = 'retro';
		}

		switch ($this->world->config('logic')) {
			case 'NoLogic':
				$logic = 'nologic';
				break;
			case 'OverworldGlitches':
				$logic = 'owglitches';
				break;
			case 'NoGLitches':
			default:
				$logic = 'noglitches';
		}

		if ($this->world->config('enemizer.bossShuffle') !== 'none') {
			$flags = array_merge($flags, [
				'--shufflebosses',
				$this->world->config('enemizer.bossShuffle'),
			]);
		}

		if ($this->world->config('dropShuffle') === 'on') {
			$flags[] = '--keydropshuffle';
		}

		if ($this->world->config('shopsanity') === 'on') {
			$flags[] = '--shopsanity';
		}

		if ($this->world->config('overworld.keepSimilar') === 'on') {
			$flags[] = '--ow_keepsimilar';
		}

		if ($this->world->config('spoil.Hints') === 'on') {
			$flags[] = '--hints';
		}

		if ($this->world->config('goal') === 'fast_ganon' && in_array($this->world->config('entrances'), ['none', 'dungeonssimple', 'dungeonsfull'])) {
			$flags[] = '--openpyramid';
		}

		$flags = array_merge(
			[
				'python3',
				'DungeonRandomizer.py',
				'--enemizercli=',
				'--mode',
				$mode,
				'--logic',
				$logic,
				'--accessibility',
				$this->world->config('accessibility'),
				'--swords',
				$this->swords_lookup[$this->world->config('mode.weapons')],
				'--goal',
				$this->goal_lookup[$this->world->config('goal')],
				'--difficulty',
				$this->world->config('item.pool'),
				'--item_functionality',
				$this->world->config('item.functionality'),
				'--shuffle',
				$this->entrance_lookup[$this->world->config('entrances')],
				'--door_shuffle',
				$this->world->config('doors.shuffle'),
				'--intensity',
				$this->world->config('doors.intensity'),
				'--ow_shuffle',
				$this->world->config('overworld.shuffle'),
				'--ow_swap',
				$this->world->config('overworld.swap'),
				'--ow_fluteshuffle',
				$this->world->config('overworld.fluteShuffle'),
				'--crystals_ganon',
				$this->world->config('crystals.ganon'),
				'--crystals_gt',
				$this->world->config('crystals.tower'),
				'--ganon_item',
				$this->world->config('ganon_item'),
				'--shufflebosses',
				$this->world->config('enemizer.bossShuffle'),
				# '--securerandom',
				'--jsonout',
				'--loglevel',
				'error',
			],
			$flags
		);

		$proc = $this->callRandomizer($flags);

		$or_output = json_decode($proc->getOutput());
		$patch = $or_output->patch_t0_p1;
		array_walk($patch, function (&$write, $address) {
			$write = [$address => $write];
		});
		$this->world->setOverridePatch(array_values((array) $patch));

		// possible temp fix
		$spoiler = json_decode($or_output->spoiler, true);
		$spoiler['meta']['build'] = Rom::BUILD_INFO[static::BRANCH]['BUILD'];
		$spoiler['meta']['logic'] = 'or-no-glitches-' . static::VERSION;

		$this->world->setSpoiler($spoiler);

		if ($this->world->config('enemizer.bossShuffle') !== 'none') {
			$this->world->getRegion('Eastern Palace')->setBoss(Boss::get($spoiler['Bosses']['Eastern Palace'], $this->world));
			$this->world->getRegion('Desert Palace')->setBoss(Boss::get($spoiler['Bosses']['Desert Palace'], $this->world));
			$this->world->getRegion('Tower of Hera')->setBoss(Boss::get($spoiler['Bosses']['Tower Of Hera'], $this->world));
			$this->world->getRegion('Palace of Darkness')->setBoss(Boss::get($spoiler['Bosses']['Palace Of Darkness'], $this->world));
			$this->world->getRegion('Swamp Palace')->setBoss(Boss::get($spoiler['Bosses']['Swamp Palace'], $this->world));
			$this->world->getRegion('Skull Woods')->setBoss(Boss::get($spoiler['Bosses']['Skull Woods'], $this->world));
			$this->world->getRegion('Thieves Town')->setBoss(Boss::get($spoiler['Bosses']['Thieves Town'], $this->world));
			$this->world->getRegion('Ice Palace')->setBoss(Boss::get($spoiler['Bosses']['Ice Palace'], $this->world));
			$this->world->getRegion('Misery Mire')->setBoss(Boss::get($spoiler['Bosses']['Misery Mire'], $this->world));
			$this->world->getRegion('Turtle Rock')->setBoss(Boss::get($spoiler['Bosses']['Turtle Rock'], $this->world));
			$this->world->getRegion('Ganons Tower')->setBoss(Boss::get($spoiler['Bosses']['Ganons Tower Basement'], $this->world), 'bottom');
			$this->world->getRegion('Ganons Tower')->setBoss(Boss::get($spoiler['Bosses']['Ganons Tower Middle'], $this->world), 'middle');
			$this->world->getRegion('Ganons Tower')->setBoss(Boss::get($spoiler['Bosses']['Ganons Tower Top'], $this->world), 'top');
		}
	}

	private function callRandomizer(array $flags, int $retries = 2): Process
	{
		$proc = new Process($flags);
		$proc->setTimeout(30);

		$proc->setWorkingDirectory(base_path('vendor/z3/overworldrandomizer'));
		Log::debug($proc->getCommandLine());

		try {
			$proc->run();
			if ($proc->isSuccessful()) {
				return $proc;
			}
			Log::info($proc->getErrorOutput());
			Log::info("overworld dungeon generation failed...");
		} catch (ProcessTimedOutException $e) {
			Log::info("overworld dungeon generation timed out...");
		}

		if ($retries > 0) {
			Log::info("retrying overworld dungeon generation...");
			return $this->callRandomizer($flags, $retries - 1);
		} else {
			throw new \Exception("Unable to generate");
		}
	}

	/**
	 * Get all the worlds being randomized.
	 *
	 * @return array
	 */
	public function getWorlds(): array
	{
		return [$this->world];
	}
}
