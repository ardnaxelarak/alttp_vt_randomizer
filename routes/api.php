<?php

use GuzzleHttp\Client;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('randomizer', 'RandomizerController@generateSeed')->middleware('throttleIp:150,360');

Route::post('mystery', 'MysteryController@generateSeed')->middleware('throttleIp:150,360');

Route::post('multiworld', 'MultiworldController@generateMultiworld')->middleware('throttleIp:40,360');

Route::post('randomizer/spoiler', 'RandomizerController@testGenerateSeed')->middleware('throttleIp:300,360');

Route::post('customizer', 'CustomizerController@generateSeed')->middleware('throttleIp:50,360');

Route::post('customizer/test', 'CustomizerController@testGenerateSeed')->middleware('throttleIp:200,360');

Route::get('daily', static function () {
    $featured = ALttP\FeaturedGame::today();
    if (!$featured) {
        $exitCode = Artisan::call('alttp:dailies', ['days' => 1]);
        $featured = ALttP\FeaturedGame::today();
    }
    $seed = $featured->seed;
    if ($seed) {
        return [
            'hash' => $seed->hash,
            'daily' => $featured->day,
        ];
    }
    abort(404);
});

Route::get('h/{hash}', static function ($hash) {
    $seed = ALttP\Seed::where('hash', $hash)->first();
    if ($seed) {
        $build = ALttP\Build::where('branch', $seed->branch)->where('build', $seed->build)->first();
        if (!$build) {
            abort(404);
        }
        return [
            'hash' => $hash,
            'md5' => $build->hash,
            'bpsLocation' => sprintf(
                '/bps/%s.bps',
                $build->hash
            ),
        ];
    }
    abort(404);
});

Route::get('generation/multiworld/{id}', static function ($id) {
    $multigen = ALttP\MultiworldGeneration::where('id', $id)->first();
    if ($multigen) {
        if ($multigen->failed) {
            return json_encode(['status' => 'failure']);
        } else if ($multigen->multiworld) {
            return json_encode([
                'status' => 'success',
                'multiworld_hash' => $multigen->multiworld->hash,
            ]);
        } else {
            return json_encode(['status' => 'waiting']);
        }
    }
    abort(404);
});

Route::get('generation/seed/{id}', static function ($id) {
    $seedgen = ALttP\SeedGeneration::where('id', $id)->first();
    if ($seedgen) {
        if ($seedgen->failed) {
            return json_encode(['status' => 'failure']);
        } else if ($seedgen->seed) {
            return json_encode([
                'status' => 'success',
                'seed_hash' => $seedgen->seed->hash,
            ]);
        } else {
            return json_encode(['status' => 'waiting']);
        }
    }
    abort(404);
});

Route::post('mw/host/{hash}', static function ($hash) {
    $client = new Client();

    $multi = ALttP\Multiworld::where('hash', $hash)->first();
    if ($multi) {
        $response = $client->request('POST', config("alttp.mw_host") . "/game", [
            'json' => [
                'multidata' => array_values(unpack("C*", $multi->multidata)),
            ],
        ]);

        $data = json_decode($response->getBody());

        return json_encode($data);
    }
    abort(404);
});
