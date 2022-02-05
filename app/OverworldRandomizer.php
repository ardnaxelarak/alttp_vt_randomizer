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
    /** @var array */
    protected $worlds = [];
    /** @var array */
    protected $multidata = [];
    /** @var array */
    protected $spoiler = [];
    /** @var array */
    private $crossed_lookup = [
        'vanilla' => 'none',
        'polar' => 'polar',
        'grouped' => 'grouped',
        'limited' => 'limited',
        'chaos' => 'chaos',
    ];
    /** @var array */
    private $entrance_lookup = [
        'none' => 'vanilla',
        'full' => 'full',
        'simple' => 'simple',
        'restricted' => 'restricted',
        'crossed' => 'crossed',
        'insanity' => 'insanity',
        'lite' => 'lite',
        'lean' => 'lean',
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
        'trinity' => 'trinity',
    ];
    /** @var array */
    private $swords_lookup = [
        'randomized' => 'random',
        'assured' => 'assured',
        'vanilla' => 'vanilla',
        'swordless' => 'swordless',
        'bombs' => 'bombs',
        'assured_bombs' => 'bombs',
        'byrna' => 'byrna',
        'assured_byrna' => 'byrna',
        'somaria' => 'somaria',
        'assured_somaria' => 'somaria',
        'cane' => 'cane',
        'pseudo' => 'pseudo',
        'assured_pseudo' => 'assured_pseudo',
    ];

    /**
     * Create a new Overworld Randomizer.
     *
     * @param array  $worlds  worlds to randomize
     *
     * @return void
     */
    public function __construct(array $worlds)
    {
        foreach ($worlds as $world) {
            if (!$world instanceof World) {
                throw new \OutOfBoundsException;
            }
            $world->setBranch(static::BRANCH);
        }
        $this->worlds = $worlds;
    }

    private function getFlags(World $world): array
    {
        $flags = [];
        if ($world->config('dungeonItems') === 'b') {
            $flags = array_merge($flags, [
                '--bigkeyshuffle',
            ]);
        } else if ($world->config('dungeonItems') === 'mc') {
            $flags = array_merge($flags, [
                '--mapshuffle',
                '--compassshuffle',
            ]);
        } elseif ($world->config('dungeonItems') === 'mcs') {
            $flags = array_merge($flags, [
                '--mapshuffle',
                '--compassshuffle',
                '--keyshuffle',
            ]);
        } elseif ($world->config('dungeonItems') === 'full') {
            $flags = array_merge($flags, [
                '--mapshuffle',
                '--compassshuffle',
                '--keyshuffle',
                '--bigkeyshuffle',
            ]);
        }

        $mode = 'standard';
        if ($world instanceof World\Open) {
            $mode = 'open';
        } elseif ($world instanceof World\Inverted) {
            $mode = 'inverted';
        }
        if ($world instanceof World\Retro) {
            $mode = 'retro';
        }

        switch ($world->config('logic')) {
            case 'NoLogic':
                $logic = 'nologic';
                break;
            case 'OverworldGlitches':
                $logic = 'owglitches';
                break;
            case 'NoGlitches':
            default:
                $logic = 'noglitches';
        }

        if ($world->config('enemizer.bossShuffle') !== 'none') {
            $flags = array_merge($flags, [
                '--shufflebosses',
                $world->config('enemizer.bossShuffle'),
            ]);
        }

        if (in_array($world->config('goal'), ['triforce-hunt', 'trinity'])) {
            $flags = array_merge($flags, [
                '--triforce_pool',
                $world->config('item.count.TriforcePiece', 30),
                '--triforce_goal',
                $world->config('item.Goal.Required', 20),
            ]);
        }

        if ($world->config('dropShuffle') === 'on') {
            $flags[] = '--keydropshuffle';
        }

        if ($world->config('shopsanity') === 'on') {
            $flags[] = '--shopsanity';
        }

        if ($world->config('overworld.keepSimilar') === 'on') {
            $flags[] = '--ow_keepsimilar';
        }

        if ($world->config('overworld.mixed') === 'on') {
            $flags[] = '--ow_mixed';
        }

        if ($world->config('spoil.Hints') === 'on') {
            $flags[] = '--hints';
        }

        if ($world->config('goal') === 'fast_ganon' && in_array($world->config('entrances'), ['none', 'dungeonssimple', 'dungeonsfull'])) {
            $flags[] = '--openpyramid';
        }

        if ($world->config('mode.weapons') === 'assured_bombs') {
            $flags = array_merge($flags, [
                '--usestartinventory=true',
                '--startinventory',
                'Progressive_Bombs',
            ]);
        } else if ($world->config('mode.weapons') === 'assured_byrna') {
            $flags = array_merge($flags, [
                '--usestartinventory=true',
                '--startinventory',
                'Progressive_Cane',
            ]);
        } else if ($world->config('mode.weapons') === 'assured_somaria') {
            $flags = array_merge($flags, [
                '--usestartinventory=true',
                '--startinventory',
                'Progressive_Cane',
            ]);
        }

        $flags = array_merge(
            [
                '--mode',
                $mode,
                '--logic',
                $logic,
                '--accessibility',
                $world->config('accessibility'),
                '--swords',
                $this->swords_lookup[$world->config('mode.weapons')],
                '--goal',
                $this->goal_lookup[$world->config('goal')],
                '--difficulty',
                $world->config('item.pool'),
                '--item_functionality',
                $world->config('item.functionality'),
                '--shuffle',
                $this->entrance_lookup[$world->config('entrances')],
                '--door_shuffle',
                $world->config('doors.shuffle'),
                '--intensity',
                $world->config('doors.intensity'),
                '--ow_shuffle',
                $world->config('overworld.shuffle'),
                '--ow_crossed',
                $this->crossed_lookup[$world->config('overworld.crossed')],
                '--ow_fluteshuffle',
                $world->config('overworld.fluteShuffle'),
                '--crystals_ganon',
                $world->config('crystals.ganon'),
                '--crystals_gt',
                $world->config('crystals.tower'),
                '--ganon_item',
                $world->config('ganon_item'),
                '--shufflebosses',
                $world->config('enemizer.bossShuffle'),
            ],
            $flags
        );

        return $flags;
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

        $index = 1;
        foreach ($this->worlds as $world) {
            $player_options = $this->getFlags($world);
            if (count($this->worlds) > 1) {
                $player_options[] = "--experimental";
            }
            $options_string = implode(' ', $player_options);
            $flags[] = "--p{$index}={$options_string}";
            $index++;
        }

        if (count($this->worlds) > 1) {
            $flags[] = "--names";
            $flags[] = implode(',', array_map(fn($world): string => str_replace([" ", ","], "", $world->config('worldName')), $this->worlds));
        }

        $flags = array_merge(
            [
                'python3',
                'DungeonRandomizer.py',
                '--enemizercli=',
                '--multi',
                count($this->worlds),
            ],
            $flags,
            [
                # '--securerandom',
                '--jsonout',
                '--loglevel',
                'error',
            ],
        );

        $proc = $this->callRandomizer($flags, 0);

        $or_output = json_decode($proc->getOutput());

        // possible temp fix
        $spoiler = json_decode($or_output->spoiler, true);
        $spoiler['meta']['build'] = Rom::BUILD_INFO[static::BRANCH]['BUILD'];
        $spoiler['meta']['logic'] = 'or-no-glitches-' . static::VERSION;

        if (count($this->worlds) > 1) {
            $this->multidata = $or_output->multidata;
        }

        $this->spoiler = $spoiler;

        $index = 1;
        foreach ($this->worlds as $world) {
            $patch = $or_output->{"patch_t0_p{$index}"};
            array_walk($patch, function (&$write, $address) {
                $write = [$address => $write];
            });
            $world->setOverridePatch(array_values((array) $patch));


            $world->setSpoiler($spoiler);

            if ($world->config('enemizer.bossShuffle') !== 'none' && count($this->worlds) > 1) {
                $world->getRegion('Eastern Palace')->setBoss(Boss::get($spoiler['Bosses'][$index]['Eastern Palace'], $world));
                $world->getRegion('Desert Palace')->setBoss(Boss::get($spoiler['Bosses'][$index]['Desert Palace'], $world));
                $world->getRegion('Tower of Hera')->setBoss(Boss::get($spoiler['Bosses'][$index]['Tower Of Hera'], $world));
                $world->getRegion('Palace of Darkness')->setBoss(Boss::get($spoiler['Bosses'][$index]['Palace Of Darkness'], $world));
                $world->getRegion('Swamp Palace')->setBoss(Boss::get($spoiler['Bosses'][$index]['Swamp Palace'], $world));
                $world->getRegion('Skull Woods')->setBoss(Boss::get($spoiler['Bosses'][$index]['Skull Woods'], $world));
                $world->getRegion('Thieves Town')->setBoss(Boss::get($spoiler['Bosses'][$index]['Thieves Town'], $world));
                $world->getRegion('Ice Palace')->setBoss(Boss::get($spoiler['Bosses'][$index]['Ice Palace'], $world));
                $world->getRegion('Misery Mire')->setBoss(Boss::get($spoiler['Bosses'][$index]['Misery Mire'], $world));
                $world->getRegion('Turtle Rock')->setBoss(Boss::get($spoiler['Bosses'][$index]['Turtle Rock'], $world));
                $world->getRegion('Ganons Tower')->setBoss(Boss::get($spoiler['Bosses'][$index]['Ganons Tower Basement'], $world), 'bottom');
                $world->getRegion('Ganons Tower')->setBoss(Boss::get($spoiler['Bosses'][$index]['Ganons Tower Middle'], $world), 'middle');
                $world->getRegion('Ganons Tower')->setBoss(Boss::get($spoiler['Bosses'][$index]['Ganons Tower Top'], $world), 'top');
            } else if ($world->config('enemizer.bossShuffle') !== 'none') {
                $world->getRegion('Eastern Palace')->setBoss(Boss::get($spoiler['Bosses']['Eastern Palace'], $world));
                $world->getRegion('Desert Palace')->setBoss(Boss::get($spoiler['Bosses']['Desert Palace'], $world));
                $world->getRegion('Tower of Hera')->setBoss(Boss::get($spoiler['Bosses']['Tower Of Hera'], $world));
                $world->getRegion('Palace of Darkness')->setBoss(Boss::get($spoiler['Bosses']['Palace Of Darkness'], $world));
                $world->getRegion('Swamp Palace')->setBoss(Boss::get($spoiler['Bosses']['Swamp Palace'], $world));
                $world->getRegion('Skull Woods')->setBoss(Boss::get($spoiler['Bosses']['Skull Woods'], $world));
                $world->getRegion('Thieves Town')->setBoss(Boss::get($spoiler['Bosses']['Thieves Town'], $world));
                $world->getRegion('Ice Palace')->setBoss(Boss::get($spoiler['Bosses']['Ice Palace'], $world));
                $world->getRegion('Misery Mire')->setBoss(Boss::get($spoiler['Bosses']['Misery Mire'], $world));
                $world->getRegion('Turtle Rock')->setBoss(Boss::get($spoiler['Bosses']['Turtle Rock'], $world));
                $world->getRegion('Ganons Tower')->setBoss(Boss::get($spoiler['Bosses']['Ganons Tower Basement'], $world), 'bottom');
                $world->getRegion('Ganons Tower')->setBoss(Boss::get($spoiler['Bosses']['Ganons Tower Middle'], $world), 'middle');
                $world->getRegion('Ganons Tower')->setBoss(Boss::get($spoiler['Bosses']['Ganons Tower Top'], $world), 'top');
            }
            $index++;
        }
    }

    private function callRandomizer(array $flags, int $retries = 2): Process
    {
        $proc = new Process($flags);
        if (count($this->worlds) == 1) {
            $proc->setTimeout(240);
        } else {
            $proc->setTimeout(240 * count($this->worlds));
        }

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
        return $this->worlds;
    }

    /**
     * Get the multidata information if a multiworld was generated, or an empty array otherwise.
     *
     * @return array
     */
    public function getMultidata(): array
    {
        return $this->multidata;
    }

    /**
     * Get the spoiler for this randomization.
     *
     * @return array
     */
    public function getSpoiler(): array
    {
        return $this->spoiler;
    }
}
