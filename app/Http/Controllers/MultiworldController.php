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

        $multigen = new MultiworldGeneration;
        $multigen->save();
        GenerateMultiworld::dispatch($multigen, $request->all())->onConnection('database');
        return response()->json([
            'multiworld_generation_id' => $multigen->id,
        ], 202);
    }

    public function testGenerateMultiworld(CreateRandomizedMultiworld $request)
    {
        try {
            return response()->json(Arr::except(GenerateMultiworld::prepMultiworld($request->all(), false), ['patch', 'seed', 'hash']));
        } catch (Exception $exception) {
            if (app()->bound('sentry')) {
                app('sentry')->captureException($exception);
            }

            return response($exception->getMessage(), 409);
        }
    }
}
