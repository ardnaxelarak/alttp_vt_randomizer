<?php

namespace ALttP\Http\Controllers;

use ALttP\Http\Requests\CreateRandomizedGame;
use ALttP\Jobs\GenerateSeed;
use ALttP\SeedGeneration;
use Exception;
use Illuminate\Support\Arr;
use Log;

class RandomizerController extends Controller
{
    public function generateSeed(CreateRandomizedGame $request)
    {
        if ($request->has('lang')) {
            app()->setLocale($request->input('lang'));
        }

        $seedgen = new SeedGeneration;
        $seedgen->save();
        GenerateSeed::dispatch($seedgen, $request->all())->onConnection('database');
        return response()->json([
            'seed_generation_id' => $seedgen->id,
        ], 202);
    }

    public function testGenerateSeed(CreateRandomizedGame $request)
    {
        try {
            return response()->json(Arr::except(GenerateSeed::prepSeed($request->all(), false), ['patch', 'seed', 'hash']));
        } catch (Exception $exception) {
            if (app()->bound('sentry')) {
                app('sentry')->captureException($exception);
            }

            return response($exception->getMessage(), 409);
        }
    }
}
