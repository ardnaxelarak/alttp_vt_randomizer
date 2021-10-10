<?php

namespace ALttP\Http\Controllers;

use ALttP\Enemizer;
use ALttP\EntranceRandomizer;
use ALttP\Http\Requests\CreateRandomizedMultiworld;
use ALttP\Jobs\SendPatchToDisk;
use ALttP\Jobs\GenerateMultiworld;
use ALttP\Multiworld;
use ALttP\MultiworldGeneration;
use ALttP\OverworldRandomizer;
use ALttP\Rom;
use ALttP\World;
use Exception;
use GrahamCampbell\Markdown\Facades\Markdown;
use HTMLPurifier_Config;
use HTMLPurifier;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Log;

class MultiworldController extends Controller
{
    public function generateMultiworld(CreateRandomizedMultiworld $request)
    {
        if ($request->has('lang')) {
            app()->setLocale($request->input('lang'));
        }

        if ($request->input('async', false)) {
            $multigen = new MultiworldGeneration;
            $multigen->save();
            GenerateMultiworld::dispatch($multigen, $request->all())->onConnection('database');
            return response()->json([
                'multiworld_generation_id' => $multigen->id,
            ], 202);
        }

        try {
            $payload = $this->prepMultiworld($request);

            $return_payload = Arr::except($payload, [
                'worlds',
            ]);

            $return_payload['worlds'] = [];

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

                $return_payload['worlds'][] = [
                    'name' => $world_payload['name'],
                    'hash' => $world_payload['hash'],
                ];
            }

            return response()->json($return_payload);
        } catch (Exception $exception) {
            Log::warning($exception);
            if (app()->bound('sentry')) {
                app('sentry')->captureException($exception);
            }

            return response($exception->getMessage(), 409);
        }
    }

    public function testGenerateMultiworld(CreateRandomizedMultiworld $request)
    {
        try {
            return response()->json(Arr::except($this->prepMultiworld($request, false), ['patch', 'seed', 'hash']));
        } catch (Exception $exception) {
            if (app()->bound('sentry')) {
                app('sentry')->captureException($exception);
            }

            return response($exception->getMessage(), 409);
        }
    }

    protected function prepMultiworld(CreateRandomizedMultiworld $request, bool $save = true)
    {
        $count = count($request->input('worlds'));
        $spoiler_meta = [];

        $purifier_settings = HTMLPurifier_Config::create(config("purifier.default"));
        $purifier_settings->loadArray(config("purifier.default"));
        $purifier = new HTMLPurifier($purifier_settings);
        if ($request->filled('name')) {
            $markdowned = Markdown::convertToHtml(substr($request->input('name'), 0, 100));
            $spoiler_meta['name'] = strip_tags($purifier->purify($markdowned));
        }
        if ($request->filled('notes')) {
            $markdowned = Markdown::convertToHtml(substr($request->input('notes'), 0, 300));
            $spoiler_meta['notes'] = $purifier->purify($markdowned);
        }

        $tournament = $request->input('tournament', true);
        $spoilers = $request->input('spoilers', 'off');
        if (!$tournament) {
            $spoilers = "on";
        } else if (!in_array($request->input('spoilers', 'off'), ["on", "off", "generate", "mystery"])) {
            $spoilers = "off";
        }

        $worlds = [];
        for ($i = 1; $i <= $count; $i++) {
            $weapons = $request->input("worlds.{$i}.weapons", 'randomized');
            $crystals_ganon = $request->input("worlds.{$i}.ganon_open", '7');
            $crystals_ganon = $crystals_ganon === 'random' ? get_random_int(0, 7) : $crystals_ganon;
            $crystals_tower = $request->input("worlds.{$i}.tower_open", '7');
            $crystals_tower = $crystals_tower === 'random' ? get_random_int(0, 7) : $crystals_tower;
            $ganon_item = $request->input("worlds.${i}.ganon_item", 'default');
            $ganon_item = $ganon_item === 'random' ? get_random_ganon_item($weapons) : $ganon_item;
            $logic = [
                'none' => 'NoGlitches',
                'overworld_glitches' => 'OverworldGlitches',
                'major_glitches' => 'MajorGlitches',
                'no_logic' => 'NoLogic',
            ][$request->input("worlds.{$i}.glitches", 'none')];

            $worlds[] = World::factory($request->input("worlds.{$i}.world_state", 'standard'), [
                'worldName' => $request->input("worlds.{$i}.name", "Player ${i}"),
                'itemPlacement' => 'advanced',
                'dungeonItems' => $request->input("worlds.{$i}.dungeon_items", 'standard'),
                'dropShuffle' => $request->input("worlds.{$i}.drop_shuffle", 'off'),
                'accessibility' => $request->input("worlds.{$i}.accessibility", 'items'),
                'goal' => $request->input("worlds.{$i}.goal", 'ganon'),
                'crystals.ganon' => $crystals_ganon,
                'crystals.tower' => $crystals_tower,
                'ganon_item' => $ganon_item,
                'entrances' => $request->input("worlds.{$i}.entrance_shuffle", 'none'),
                'doors.shuffle' => $request->input("worlds.{$i}.door_shuffle", 'vanilla'),
                'doors.intensity' => $request->input("worlds.{$i}.door_intensity", '1'),
                'overworld.shuffle' => $request->input("worlds.{$i}.ow_shuffle", 'vanilla'),
                'overworld.crossed' => $request->input("worlds.{$i}.ow_crossed", 'vanilla'),
                'overworld.keepSimilar' => $request->input("worlds.{$i}.ow_keep_similar", 'off'),
                'overworld.mixed' => $request->input("worlds.{$i}.ow_mixed", 'off'),
                'overworld.fluteShuffle' => $request->input("worlds.{$i}.ow_flute_shuffle", 'vanilla'),
                'shopsanity' => $request->input("worlds.{$i}.shopsanity", 'off'),
                'mode.weapons' => $weapons,
                'tournament' => $tournament,
                'spoilers' => $spoilers,
                'allow_quickswap' => $request->input('allow_quickswap', true),
                'override_start_screen' => $request->input("worlds.{$i}.override_start_screen", false),
                'spoil.Hints' => $request->input("worlds.{$i}.hints", 'on'),
                'logic' => $logic,
                'item.pool' => $request->input("worlds.{$i}.item_pool", 'normal'),
                'item.functionality' => $request->input("worlds.{$i}.item_functionality", 'normal'),
                'enemizer.bossShuffle' => $request->input("worlds.{$i}.boss_shuffle", 'none'),
                'enemizer.enemyShuffle' => $request->input("worlds.{$i}.enemy_shuffle", 'none'),
                'enemizer.enemyDamage' => $request->input("worlds.{$i}.enemy_damage", 'default'),
                'enemizer.enemyHealth' => $request->input("worlds.{$i}.enemy_health", 'default'),
                'enemizer.potShuffle' => $request->input("worlds.{$i}.pot_shuffle", 'off'),
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
            'hash' => $multi->hash,
        ];
    }
}

