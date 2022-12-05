<?php

use ALttP\Enemizer;
use ALttP\Jobs\SendPatchToDisk;
use ALttP\Rom;
use ALttP\Support\RandomizerSelector;
use ALttP\Support\WorldCollection;
use ALttP\World;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

/**
 * This file is for one off console commands. Ideally all of these should be
 * properly coded into real commands if they stick around for a while.
 *
 * @todo convert all current commands here to classes
 */

if (!function_exists('getWeighted')) {
    function getWeighted(string $category): string
    {
        Log::info($category);
        $keys = array_keys(config("alttp.randomizer.item.$category"));
        $combined = array_combine($keys, $keys);
        $weights = config("alttp.randomizer.daily_weights.$category");

        return head(weighted_random_pick($combined, $weights));
    }
}

Artisan::command('alttp:dailies {days=7}', function ($days) {
    for ($i = 0; $i < $days; ++$i) {
        $date = Carbon::now()->addDays($i);
        $feature = ALttP\FeaturedGame::firstOrNew([
            'day' => $date->toDateString(),
        ]);
        if (!$feature->exists) {
            $weapons = getWeighted('weapons');
            $entry_crystals_ganon = getWeighted('ganon_open');
            $crystals_ganon = $entry_crystals_ganon === 'random' ? get_random_int(0, 7) : $entry_crystals_ganon;
            $entry_crystals_tower = getWeighted('tower_open');
            $crystals_tower = $entry_crystals_tower === 'random' ? get_random_int(0, 7) : $entry_crystals_tower;
            $entry_ganon_item = getWeighted('ganon_item');
            $ganon_item = $entry_ganon_item === 'random' ? get_random_ganon_item($weapons) : $entry_ganon_item;
            $logic = [
                'none' => 'NoGlitches',
                'overworld_glitches' => 'OverworldGlitches',
                'major_glitches' => 'MajorGlitches',
                'no_logic' => 'NoLogic',
            ][getWeighted('glitches_required')];

            $world = World::factory(1, getWeighted('world_state'), [
                'itemPlacement' => getWeighted('item_placement'),
                'dungeonItems' => getWeighted('dungeon_items'),
                'bossItems' => getWeighted('boss_items'),
                'dropShuffle' => getWeighted('drop_shuffle'),
                'bonkShuffle' => getWeighted('bonk_shuffle'),
                'potteryShuffle' => getWeighted('pottery_shuffle'),
                'accessibility' => getWeighted('accessibility'),
                'goal' => getWeighted('goals'),
                'crystals.ganon' => $crystals_ganon,
                'crystals.tower' => $crystals_tower,
                'ganon_item' => $ganon_item,
                'entrances' => getWeighted('entrance_shuffle'),
                'doors.shuffle' => getWeighted('door_shuffle'),
                'doors.intensity' => getWeighted('door_intensity'),
                'overworld.shuffle' => getWeighted('ow_shuffle'),
                'overworld.crossed' => getWeighted('ow_crossed'),
                'overworld.keepSimilar' => getWeighted('ow_keep_similar'),
                'overworld.mixed' => getWeighted('ow_mixed'),
                'overworld.fluteShuffle' => getWeighted('ow_flute_shuffle'),
                'overworld.whirlpoolShuffle' => getWeighted('ow_whirlpool_shuffle'),
                'shopsanity' => getWeighted('shopsanity'),
                'mode.weapons' => $weapons,
                'tournament' => true,
                'spoil.Hints' => getWeighted('hints'),
                'spoilers' => getWeighted('spoilers'),
                'logic' => $logic,
                'item.pool' => getWeighted('item_pool'),
                'item.functionality' => getWeighted('item_functionality'),
                'equipment.flute' => getWeighted('starting_flute'),
                'equipment.boots' => getWeighted('starting_boots'),
                'enemizer.bossShuffle' => getWeighted('boss_shuffle'),
                'enemizer.enemyShuffle' => getWeighted('enemy_shuffle'),
                'enemizer.enemyDamage' => getWeighted('enemy_damage'),
                'enemizer.enemyHealth' => getWeighted('enemy_health'),
                'enemizer.potShuffle' => 'off',
            ]);

            $rand = RandomizerSelector::getRandomizer($world);

            $rom = new Rom(config('alttp.base_rom'));
            $rom->applyPatchFile(Rom::getJsonPatchLocation($world->config('branch')));

            $rand->randomize();

            if ($world->isEnemized()) {
                $patch = $rom->getWriteLog();
                $en = new Enemizer($world, $patch);
                $en->randomize($world->config('branch'));
                $en->writeToRom($rom);
            }

            $world->writeToRom($rom, true);

            // Entrance rando and overworld rando are responsible for verifying winnability of themselves
            // and generating their own full spoilers
            if ($rand instanceof \ALttP\Randomizer) {
                $worlds = new WorldCollection($rand->getWorlds());

                if (!$worlds->isWinnable()) {
                    throw new Exception('Game Unwinnable');
                }
            }

            $rom->setTournamentType('none');

            $patch = $rom->getWriteLog();

            $world->updateSeedRecordPatch($patch);

            $spoiler = $world->getSpoiler([
                'name' => 'Daily Challenge: ' . $date->toFormattedDateString(),
                'entry_crystals_ganon' => $entry_crystals_ganon,
                'entry_crystals_tower' => $entry_crystals_tower,
                'ganon_item' => $entry_ganon_item,
                'worlds' => 1,
            ]);

            switch ($spoiler['meta']['spoilers']) {
                case "on":
                case "generate":
                    $spoiler = Arr::except($spoiler, [
                        'spoiler.playthrough',
                    ]);
                    break;
                case "mystery":
                    $spoiler = Arr::only($spoiler, ['meta']);
                    $spoiler['meta'] = Arr::only($spoiler['meta'], [
                        'name',
                        'notes',
                        'logic',
                        'build',
                        'tournament',
                        'spoilers',
                        'size'
                    ]);
                    break;
                case "off":
                default:
                    $spoiler = Arr::except(Arr::only($spoiler, [
                        'meta',
                    ]), ['meta.seed']);
            }

            $seed_record = $world->getSeedRecord();

            $feature->seed_id = $seed_record->id;
            $feature->description = vsprintf("%s %s %s", [
                $world->config('goal'),
                $world->config('mode.weapons'),
                $world->config('logic'),
            ]);
            $feature->save();

            $spoiler = Arr::except(
                Arr::only($spoiler, ['meta']),
                [
                    'meta.seed',
                    'meta.crystals_ganon',
                    'meta.crystals_tower',
                    'meta.ganon_item'
                ]
            );

            $save_data = json_encode([
                'logic' => $world->config('logic'),
                'patch' => patch_merge_minify($patch),
                'spoiler' => $spoiler,
                'hash' => $seed_record->hash,
                'generated' => $seed_record->created_at ? $seed_record->created_at->toIso8601String() : now()->toIso8601String(),
                'size' => $spoiler['meta']['size'] ?? 2,
            ]);

            $seed_record->save();
            SendPatchToDisk::dispatch($seed_record);
            cache(['hash.' . $seed_record->hash => $save_data], now()->addDays(7));
        }
    }
});

Artisan::command('alttp:compressgfx {input} {output}', function ($input, $output) {
    if (!is_readable($input)) {
        return $this->error("Can't read file");
    }
    if (file_exists($output) && !is_writable($output) || !is_writable(dirname($output))) {
        return $this->error("Can't write file");
    }

    $lz2 = new ALttP\Support\Lz2();
    file_put_contents($output, pack('C*', ...$lz2->compress(array_values(unpack("C*", file_get_contents($input))))));

    $this->info(sprintf('Compressed: `%s` to `%s`', $input, $output));
});

Artisan::command('alttp:decompressgfx {input} {output}', function ($input, $output) {
    if (!is_readable($input)) {
        return $this->error("Can't read file");
    }
    if (file_exists($output) && !is_writable($output) || !is_writable(dirname($output))) {
        return $this->error("Can't write file");
    }

    $lz2 = new ALttP\Support\Lz2();
    file_put_contents($output, pack('C*', ...$lz2->decompress(array_values(unpack("C*", file_get_contents($input))))));

    $this->info(sprintf('Decompressed: `%s` to `%s`', $input, $output));
});

Artisan::command('alttp:sprpub', function () {
    foreach (Storage::disk('sprites')->allFiles('') as $file) {
        if (preg_match('/\.DS_Store$/', $file)) {
            continue;
        }
        if (preg_match('/\.gitignore$/', $file)) {
            continue;
        }
        if (preg_match('/link\.1\.zspr$/', $file)) {
            continue;
        }
        if (Storage::disk('images')->exists($file)) {
            continue;
        }

        $this->info($file);
        Storage::disk('images')->put($file, Storage::disk('sprites')->get($file), [
            'headers' => [
                'Access-Control-Expose-Headers' => 'Access-Control-Allow-Origin',
                'Access-Control-Allow-Origin' => '*',
            ]
        ]);
    }
});
