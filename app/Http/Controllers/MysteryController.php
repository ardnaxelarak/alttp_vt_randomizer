<?php

namespace ALttP\Http\Controllers;

use ALttP\Http\Requests\CreateMysteryGame;
use ALttP\Jobs\GenerateSeed;
use ALttP\SeedGeneration;
use ALttP\Support\MysteryRoller;
use Exception;
use Log;

class MysteryController extends Controller
{
    public function generateSeed(CreateMysteryGame $request)
    {
        if ($request->has('lang')) {
            app()->setLocale($request->input('lang'));
        }

        $randomizedRequest = MysteryRoller::getSettings($request->input('weights'));

        if ($request->has('tournament')) {
            $randomizedRequest['tournament'] = $request->input('tournament');
        }

        $seedgen = new SeedGeneration;
        $seedgen->save();
        GenerateSeed::dispatch($seedgen, $randomizedRequest)->onConnection('database');
        return response()->json([
            'seed_generation_id' => $seedgen->id,
        ], 202);
    }
}

