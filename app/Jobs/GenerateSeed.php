<?php

namespace ALttP\Jobs;

use ALttP\Enemizer;
use ALttP\OverworldRandomizer;
use ALttP\Randomizer;
use ALttP\Rom;
use ALttP\SeedGeneration;
use ALttP\Support\RandomizerSelector;
use ALttP\Support\WorldCollection;
use ALttP\World;
use GrahamCampbell\Markdown\Facades\Markdown;
use HTMLPurifier_Config;
use HTMLPurifier;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Throwable;

class GenerateSeed implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 10;

    protected SeedGeneration $seedgen;
    protected array $request;

    public function __construct(SeedGeneration $seedgen, array $request)
    {
        $this->seedgen = $seedgen;
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $payload = GenerateSeed::prepSeed($this->request, true);
        $payload['seed']->save();
        SendPatchToDisk::dispatch($payload['seed']);

        $return_payload = Arr::except($payload, [
            'seed',
            'spoiler.meta.crystals_ganon',
            'spoiler.meta.crystals_tower',
            'spoiler.meta.ganon_item',
        ]);

        if ($payload['spoiler']['meta']['tournament'] ?? false) {
            switch ($payload['spoiler']['meta']['spoilers']) {
                case "on":
                case "generate":
                    $return_payload = Arr::except($return_payload, [
                        'spoiler.playthrough',
                    ]);
                    break;
                case "mystery":
                    $return_payload['spoiler'] = Arr::only($return_payload['spoiler'], ['meta']);
                    $return_payload['spoiler']['meta'] = Arr::only($return_payload['spoiler']['meta'], [
                        'name',
                        'notes',
                        'logic',
                        'build',
                        'tournament',
                        'spoilers',
                        'size',
                        'special',
                        'allow_quickswap'
                    ]);
                    break;
                case "off":
                default:
                    $return_payload['spoiler'] = Arr::except(Arr::only($return_payload['spoiler'], [
                        'meta',
                    ]), ['meta.seed']);
            }
        }

        $cached_payload = $return_payload;
        if ($payload['spoiler']['meta']['spoilers'] === 'generate') {
            // ensure that the cache doesn't have the spoiler, but the original return_payload still does
            $cached_payload['spoiler'] = Arr::except(Arr::only($return_payload['spoiler'], [
                'meta',
            ]), ['meta.seed']);
        }
        $save_data = json_encode(Arr::except($cached_payload, [
            'current_rom_hash',
        ]));
        Cache::put('hash.' . $payload['hash'], $save_data, now()->addDays(7));

        // TODO: figure out what to do with the return payload

        $this->seedgen->seed_id = $payload['id'];
        $this->seedgen->save();
    }

    /**
     * Handle a job failure.
     *
     * param  Throwable  $exception
     * @return void
     */
    public function failed(Throwable $exception)
    {
        $this->seedgen->failed = true;
        $this->seedgen->save();
    }

    public static function prepSeed(array $request, bool $save = true)
    {
        $weapons = Arr::get($request, 'weapons', 'randomized');
        $crystals_ganon = Arr::get($request, 'crystals.ganon', '7');
        $crystals_ganon = $crystals_ganon === 'random' ? get_random_int(0, 7) : $crystals_ganon;
        $crystals_ganon = $crystals_ganon === 'low_random' ? get_random_int(1, 4) : $crystals_ganon;
        $crystals_tower = Arr::get($request, 'crystals.tower', '7');
        $crystals_tower = $crystals_tower === 'random' ? get_random_int(0, 7) : $crystals_tower;
        $ganon_item = Arr::get($request, 'ganon_item', 'default');
        $ganon_item = $ganon_item === 'random' ? get_random_ganon_item($weapons) : $ganon_item;
        $logic = [
            'none' => 'NoGlitches',
            'overworld_glitches' => 'OverworldGlitches',
            'major_glitches' => 'MajorGlitches',
            'no_logic' => 'NoLogic',
        ][Arr::get($request, 'glitches', 'none')];

        $spoilers = Arr::get($request, 'spoilers', 'off');
        if (!Arr::get($request, 'tournament', true)) {
            $spoilers = "on";
        } else if (!in_array(Arr::get($request, 'spoilers', 'off'), ["on", "off", "generate", "mystery"])) {
            $spoilers = "off";
        }

        // quick fix for CC and Basic
        if (Arr::get($request, 'item.pool', 'normal') === 'crowd_control') {
            $request->merge(['item_placement' => 'advanced']);
        }

        $spoiler_meta = [];

        $purifier_settings = HTMLPurifier_Config::create(config("purifier.default"));
        $purifier_settings->loadArray(config("purifier.default"));
        $purifier = new HTMLPurifier($purifier_settings);
        if (Arr::has($request, 'name')) {
            $markdowned = Markdown::convertToHtml(substr(Arr::get($request, 'name'), 0, 100));
            $spoiler_meta['name'] = strip_tags($purifier->purify($markdowned));
        }
        if (Arr::has($request, 'notes')) {
            $markdowned = Markdown::convertToHtml(substr(Arr::get($request, 'notes'), 0, 300));
            $spoiler_meta['notes'] = $purifier->purify($markdowned);
        }

        $world = World::factory(1, Arr::get($request, 'mode', 'standard'), [
            'itemPlacement' => Arr::get($request, 'item_placement', 'basic'),
            'dungeonItems' => Arr::get($request, 'dungeon_items', 'standard'),
            'dropShuffle' => Arr::get($request, 'drop_shuffle', 'off'),
            'accessibility' => Arr::get($request, 'accessibility', 'items'),
            'goal' => Arr::get($request, 'goal', 'ganon'),
            'crystals.ganon' => $crystals_ganon,
            'crystals.tower' => $crystals_tower,
            'ganon_item' => $ganon_item,
            'entrances' => Arr::get($request, 'entrances', 'none'),
            'doors.shuffle' => Arr::get($request, 'doors.shuffle', 'vanilla'),
            'doors.intensity' => Arr::get($request, 'doors.intensity', '1'),
            'overworld.shuffle' => Arr::get($request, 'overworld.shuffle', 'vanilla'),
            'overworld.crossed' => Arr::get($request, 'overworld.crossed', 'vanilla'),
            'overworld.keepSimilar' => Arr::get($request, 'overworld.keep_similar', 'off'),
            'overworld.mixed' => Arr::get($request, 'overworld.mixed', 'off'),
            'overworld.fluteShuffle' => Arr::get($request, 'overworld.flute_shuffle', 'vanilla'),
            'shopsanity' => Arr::get($request, 'shopsanity', 'off'),
            'mode.weapons' => $weapons,
            'tournament' => Arr::get($request, 'tournament', false),
            'spoilers' => $spoilers,
            'allow_quickswap' => Arr::get($request, 'allow_quickswap', true),
            'override_start_screen' => Arr::get($request, 'override_start_screen', false),
            'spoil.Hints' => Arr::get($request, 'hints', 'on'),
            'logic' => $logic,
            'item.pool' => Arr::get($request, 'item.pool', 'normal'),
            'item.functionality' => Arr::get($request, 'item.functionality', 'normal'),
            'enemizer.bossShuffle' => Arr::get($request, 'enemizer.boss_shuffle', 'none'),
            'enemizer.enemyShuffle' => Arr::get($request, 'enemizer.enemy_shuffle', 'none'),
            'enemizer.enemyDamage' => Arr::get($request, 'enemizer.enemy_damage', 'default'),
            'enemizer.enemyHealth' => Arr::get($request, 'enemizer.enemy_health', 'default'),
            'enemizer.potShuffle' => Arr::get($request, 'enemizer.pot_shuffle', 'off'),
        ]);

        $rand = RandomizerSelector::getRandomizer($world);

        $rom = new Rom(config('alttp.base_rom'));
        $rom->applyPatchFile(Rom::getJsonPatchLocation($world->config('branch')));

        $rand->randomize();
        $world->writeToRom($rom, $save);

        $processSpoiler = false;

        // Entrance rando and overworld rando are responsible for verifying winnability of themselves
        // and generating their own full spoilers
        if ($rand instanceof \ALttP\Randomizer) {
            $processSpoiler = true;
            $worlds = new WorldCollection($rand->getWorlds());

            if (!$worlds->isWinnable()) {
                throw new \Exception('Game Unwinnable');
            }
        }

        $spoiler = $world->getSpoiler(array_merge($spoiler_meta, [
            'entry_crystals_ganon' => Arr::get($request, 'crystals.ganon', '7'),
            'entry_crystals_tower' => Arr::get($request, 'crystals.tower', '7'),
            'ganon_vulnerability_item' => Arr::get($request, 'ganon_item', 'default'),
            'worlds' => 1,
        ]), $processSpoiler);

        if ($world->isEnemized()) {
            $patch = $rom->getWriteLog();
            $en = new Enemizer($world, $patch);
            $en->randomize($world->config('branch'));
            $en->writeToRom($rom);
        }

        if (Arr::get($request, 'tournament', false)) {
            $rom->setTournamentType('standard');
            $rom->rummageTable();
        }
        $patch = $rom->getWriteLog();

        if ($save) {
            $world->updateSeedRecordPatch($patch);
        }

        return [
            'logic' => $world->config('logic'),
            'patch' => patch_merge_minify($patch),
            'branch' => $world->config('branch'),
            'spoiler' => $spoiler,
            'id' => $world->getSeedRecord()->id,
            'hash' => $world->getSeedRecord()->hash,
            'generated' => $world->getSeedRecord()->created_at ? $world->getSeedRecord()->created_at->toIso8601String() : now()->toIso8601String(),
            'seed' => $world->getSeedRecord(),
            'size' => $spoiler['meta']['size'] ?? 2,
            'current_rom_hash' => Rom::BUILD_INFO[$world->config('branch')]['HASH'],
        ];
    }
}

