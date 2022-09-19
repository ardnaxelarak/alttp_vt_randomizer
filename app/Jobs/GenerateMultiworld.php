<?php

namespace ALttP\Jobs;

use ALttP\Enemizer;
use ALttP\Multiworld;
use ALttP\MultiworldGeneration;
use ALttP\OverworldRandomizer;
use ALttP\Rom;
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

class GenerateMultiworld implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 10;

    protected MultiworldGeneration $multigen;
    protected array $request;

    public function __construct(MultiworldGeneration $multigen, array $request)
    {
        $this->multigen = $multigen;
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $payload = GenerateMultiworld::prepMultiworld($this->request, true);

        foreach ($payload['worlds'] as $world_payload) {
            $world_payload['seed']->save();
            SendPatchToDisk::dispatch($world_payload['seed']);

            $cached_payload = Arr::except($world_payload, [
                'seed',
                'spoiler.meta.crystals_ganon',
                'spoiler.meta.crystals_tower',
                'spoiler.meta.ganon_item',
            ]);

            if ($world_payload['spoiler']['meta']['tournament'] ?? false) {
                switch ($world_payload['spoiler']['meta']['spoilers']) {
                    case "on":
                    case "generate":
                        $cached_payload = Arr::except($cached_payload, [
                            'spoiler.playthrough',
                        ]);
                        break;
                    case "mystery":
                        $cached_payload['spoiler'] = Arr::only($cached_payload['spoiler'], ['meta']);
                        $cached_payload['spoiler']['meta'] = Arr::only($cached_payload['spoiler']['meta'], [
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
                        $cached_payload['spoiler'] = Arr::except(Arr::only($cached_payload['spoiler'], [
                            'meta',
                        ]), ['meta.seed']);
                }
            }

            if ($world_payload['spoiler']['meta']['spoilers'] === 'generate') {
                // ensure that the cache doesn't have the spoiler, but the original return_payload still does
                $cached_payload['spoiler'] = Arr::except(Arr::only($cached_payload['spoiler'], [
                    'meta',
                ]), ['meta.seed']);
            }
            $save_data = json_encode(Arr::except($cached_payload, [
                'current_rom_hash',
            ]));
            Cache::put('hash.' . $world_payload['hash'], $save_data, now()->addDays(7));
        }

        $this->multigen->multi_id = $payload['id'];
        $this->multigen->save();
    }

    /**
     * Handle a job failure.
     *
     * param  Throwable  $exception
     * @return void
     */
    public function failed(Throwable $exception)
    {
        $this->multigen->failed = true;
        $this->multigen->save();
    }

    public static function prepMultiworld(array $request, bool $save = true)
    {
        $count = count(Arr::get($request, 'worlds'));
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

        $tournament = Arr::get($request, 'tournament', true);
        $spoilers = Arr::get($request, 'spoilers', 'off');
        if (!$tournament) {
            $spoilers = "on";
        } else if (!in_array(Arr::get($request, 'spoilers', 'off'), ["on", "off", "generate", "mystery"])) {
            $spoilers = "off";
        }

        $worlds = [];
        for ($i = 1; $i <= $count; $i++) {
            $worldName = Arr::get($request, "worlds.{$i}.name", "Player ${i}");
            if (!$worldName) {
                $worldName = "Player ${i}";
            }
            $weapons = Arr::get($request, "worlds.{$i}.weapons", 'randomized');
            $crystals_ganon = Arr::get($request, "worlds.{$i}.ganon_open", '7');
            $crystals_ganon = $crystals_ganon === 'random' ? get_random_int(0, 7) : $crystals_ganon;
            $crystals_ganon = $crystals_ganon === 'low_random' ? get_random_int(1, 4) : $crystals_ganon;
            $crystals_tower = Arr::get($request, "worlds.{$i}.tower_open", '7');
            $crystals_tower = $crystals_tower === 'random' ? get_random_int(0, 7) : $crystals_tower;
            $ganon_item = Arr::get($request, "worlds.${i}.ganon_item", 'default');
            $ganon_item = $ganon_item === 'random' ? get_random_ganon_item($weapons) : $ganon_item;
            $logic = [
                'none' => 'NoGlitches',
                'overworld_glitches' => 'OverworldGlitches',
                'major_glitches' => 'MajorGlitches',
                'no_logic' => 'NoLogic',
            ][Arr::get($request, "worlds.{$i}.glitches", 'none')];

            $worlds[] = World::factory($i, Arr::get($request, "worlds.{$i}.world_state", 'standard'), [
                'worldName' => $worldName,
                'itemPlacement' => 'advanced',
                'dungeonItems' => Arr::get($request, "worlds.{$i}.dungeon_items", 'standard'),
                'dropShuffle' => Arr::get($request, "worlds.{$i}.drop_shuffle", 'off'),
                'bonkShuffle' => Arr::get($request, "worlds.{$i}.bonk_shuffle", 'off'),
                'potteryShuffle' => Arr::get($request, "worlds.{$i}.pottery_shuffle", 'none'),
                'accessibility' => Arr::get($request, "worlds.{$i}.accessibility", 'items'),
                'goal' => Arr::get($request, "worlds.{$i}.goal", 'ganon'),
                'crystals.ganon' => $crystals_ganon,
                'crystals.tower' => $crystals_tower,
                'ganon_item' => $ganon_item,
                'entrances' => Arr::get($request, "worlds.{$i}.entrance_shuffle", 'none'),
                'doors.shuffle' => Arr::get($request, "worlds.{$i}.door_shuffle", 'vanilla'),
                'doors.intensity' => Arr::get($request, "worlds.{$i}.door_intensity", '1'),
                'overworld.shuffle' => Arr::get($request, "worlds.{$i}.ow_shuffle", 'vanilla'),
                'overworld.crossed' => Arr::get($request, "worlds.{$i}.ow_crossed", 'vanilla'),
                'overworld.keepSimilar' => Arr::get($request, "worlds.{$i}.ow_keep_similar", 'off'),
                'overworld.mixed' => Arr::get($request, "worlds.{$i}.ow_mixed", 'off'),
                'overworld.fluteShuffle' => Arr::get($request, "worlds.{$i}.ow_flute_shuffle", 'vanilla'),
                'shopsanity' => Arr::get($request, "worlds.{$i}.shopsanity", 'off'),
                'mode.weapons' => $weapons,
                'tournament' => $tournament,
                'spoilers' => $spoilers,
                'allow_quickswap' => Arr::get($request, 'allow_quickswap', true),
                'override_start_screen' => Arr::get($request, "worlds.{$i}.override_start_screen", false),
                'spoil.Hints' => Arr::get($request, "worlds.{$i}.hints", 'on'),
                'logic' => $logic,
                'item.pool' => Arr::get($request, "worlds.{$i}.item_pool", 'normal'),
                'item.functionality' => Arr::get($request, "worlds.{$i}.item_functionality", 'normal'),
                'enemizer.bossShuffle' => Arr::get($request, "worlds.{$i}.boss_shuffle", 'none'),
                'enemizer.enemyShuffle' => Arr::get($request, "worlds.{$i}.enemy_shuffle", 'none'),
                'enemizer.enemyDamage' => Arr::get($request, "worlds.{$i}.enemy_damage", 'default'),
                'enemizer.enemyHealth' => Arr::get($request, "worlds.{$i}.enemy_health", 'default'),
                'enemizer.potShuffle' => Arr::get($request, "worlds.{$i}.pot_shuffle", 'off'),
            ]);
        }

        $rand = new OverworldRandomizer($worlds);
        $rand->randomize();

        $world_payloads = [];

        $multi = new Multiworld;
        $multi->spoiler = json_encode($rand->getSpoiler());
        $multi->multidata = pack('C*', ...$rand->getMultidata());
        if ($save) {
            $multi->save();
        }

        foreach ($worlds as $world) {
            $rom = new Rom(config('alttp.base_rom'));
            $rom->applyPatchFile(Rom::getJsonPatchLocation($world->config('branch')));
            $world->writeToRom($rom, $save, false);

            // Overworld rando is responsible for verifying winnability of itself
            // and generating its own full spoiler
            /*
            $spoiler = $world->getSpoiler(array_merge($spoiler_meta, [
                'entry_crystals_ganon' => $request->input('crystals.ganon', '7'),
                'entry_crystals_tower' => $request->input('crystals.tower', '7'),
                'ganon_vulnerability_item' => $request->input('ganon_item', 'default'),
                'worlds' => 1,
            ]), false);
            */
            $spoiler = $world->getSpoiler($spoiler_meta, false);

            if ($world->isEnemized()) {
                $patch = $rom->getWriteLog();
                $en = new Enemizer($world, $patch);
                $en->randomize($world->config('branch'));
                $en->writeToRom($rom);
            }

            if ($tournament) {
                $rom->setTournamentType('standard');
                $rom->rummageTable();
            }
            $patch = $rom->getWriteLog();

            if ($save) {
                $world->updateSeedRecordPatch($patch);
                $world->updateSeedRecordMultiworld($multi);
            }

            $world_payloads[] = [
                'name' => $world->config('worldName'),
                'logic' => $world->config('logic'),
                'patch' => patch_merge_minify($patch),
                'branch' => $world->config('branch'),
                'spoiler' => $spoiler,
                'hash' => $world->getSeedRecord()->hash,
                'generated' => $world->getSeedRecord()->created_at ? $world->getSeedRecord()->created_at->toIso8601String() : now()->toIso8601String(),
                'seed' => $world->getSeedRecord(),
                'size' => $spoiler['meta']['size'] ?? 2,
                'current_rom_hash' => Rom::BUILD_INFO[$world->config('branch')]['HASH'],
            ];
        }

        return [
            'worlds' => $world_payloads,
            'multidata' => $rand->getMultidata(),
            'spoiler' => $rand->getSpoiler(),
            'generated' => $multi->created_at ? $multi->created_at->toIso8601String() : now()->toIso8601String(),
            'id' => $multi->id,
            'hash' => $multi->hash,
        ];
    }
}
