<?php

namespace ALttP\Http\Controllers;

use ALttP\EntranceRandomizer;
use Illuminate\Http\Request;
use ALttP\Jobs\SendPatchToDisk;
use ALttP\Randomizer;
use ALttP\Rom;
use ALttP\Support\WorldCollection;
use ALttP\World;
use Exception;

class MultiworldController extends Controller
{
    public function generateSeed(Request $request)
    {
        return response('not implemented', 404);

        if ($request->has('lang')) {
            app()->setLocale($request->input('lang'));
        }

        try {
            $payload = $this->prepSeed($request);
            //$payload['seed']->save();
            //SendPatchToDisk::dispatch($payload['seed']);

            return response($payload, 200)
                ->header('Content-Type', 'application/octet-stream');
        } catch (Exception $exception) {
            if (app()->bound('sentry')) {
                app('sentry')->captureException($exception);
            }

            return response($exception->getMessage(), 409);
        }
    }

    protected function prepSeed(Request $request)
    {
        $worlds = [];

        set_time_limit(300);

        foreach ($request->input('worlds') as $config) {
            $weapons = $config['weapons'] ?? 'randomized';
            $crystals_ganon = $config['crystals.ganon'] ?? '7';
            $crystals_ganon = $crystals_ganon === 'random' ? get_random_int(0, 7) : $crystals_ganon;
            $crystals_tower = $config['crystals.tower'] ?? '7';
            $crystals_tower = $crystals_tower === 'random' ? get_random_int(0, 7) : $crystals_tower;
            $ganon_item = $config['ganon_item'] ?? 'default';
            $ganon_item = $ganon_item === 'random' ? get_random_ganon_item($weapons) : $ganon_item;
            $logic = [
                'none' => 'NoGlitches',
                'overworld_glitches' => 'OverworldGlitches',
                'major_glitches' => 'MajorGlitches',
                'no_logic' => 'NoLogic',
            ][$config['glitches'] ?? 'none'];

            // quick fix for CC and Basic/Entrance
            if (($config['item.pool'] ?? 'normal') === 'crowd_control') {
                $request->merge(['item_placement' => 'advanced']);
                $request->merge(['entrances' => 'none']);
            }

            $worlds[] = World::factory($config['mode'] ?? 'standard', [
                'itemPlacement' => $config['item_placement'] ?? 'basic',
                'dungeonItems' => $config['dungeon_items'] ?? 'standard',
                'dropShuffle' => $config['drop_shuffle'] ?? 'off',
                'accessibility' => $config['accessibility'] ?? 'items',
                'goal' => $config['goal'] ?? 'ganon',
                'crystals.ganon' => $crystals_ganon,
                'crystals.tower' => $crystals_tower,
                'ganon_item' => $ganon_item,
                'entrances' => $config['entrances'] ?? 'none',
                'doors.shuffle' => $config['door_shuffle'] ?? 'vanilla',
                'doors.intensity' => $config['door_intensity'] ?? '1',
                'overworld.shuffle' => $config['ow_shuffle'] ?? 'vanilla',
                'overworld.keepSimilar' => $config['ow_keep_similar'] ?? 'off',
                'overworld.swap' => $config['ow_swap'] ?? 'vanilla',
                'overworld.fluteShuffle' => $config['ow_flute_shuffle'] ?? 'vanilla',
                'shopsanity' => $config['shopsanity'] ?? 'off',
                'mode.weapons' => $weapons,
                'tournament' => $config['tournament'] ?? false,
                'spoilers' => $config['spoilers'] ?? false,
                'spoilers_ongen' => $config['spoilers_ongen'] ?? false,
                'spoil.Hints' => $config['hints'] ?? 'on',
                'logic' => $logic,
                'item.pool' => $config['item.pool'] ?? 'normal',
                'item.functionality' => $config['item.functionality'] ?? 'normal',
                'enemizer.bossShuffle' => 'none',
                'enemizer.enemyShuffle' => 'none',
                'enemizer.enemyDamage' => 'default',
                'enemizer.enemyHealth' => 'default',
                'enemizer.potShuffle' => 'off',
                'multiworld' => true,
            ]);
        }

        $rand = RandomizerSelector::getRandomizer($world);

        $rand->randomize();

        // E.R. is responsible for verifying winnability of itself
        //if ($world->config('entrances') === 'none') {
        $worlds = new WorldCollection($rand->getWorlds());

        if (!$worlds->isWinnable()) {
            throw new Exception('Game Unwinnable');
        }
        //}

        $spoiler = $worlds->getSpoiler([
            'worlds' => $worlds->count(),
        ]);

        foreach ($spoiler as $worldId => $worldSpoiler) {
            $rom = new Rom(config('alttp.base_rom'));
            $rom->applyPatchFile(Rom::getJsonPatchLocation($worlds[$worldId]->config('branch')));

            $worlds->get($worldId)->writeToRom($rom, false);

            $writeLog = patch_merge_minify($rom->getWriteLog());
            $spoiler[$worldId]['writeData'] = $writeLog;
        }

        // the .mw file
        return gzencode(json_encode($spoiler));
    }
}
