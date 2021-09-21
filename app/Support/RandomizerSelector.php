<?php

namespace ALttP\Support;

use ALttP\World;
use ALttP\EntranceRandomizer;
use ALttP\OverworldRandomizer;
use ALttP\Randomizer;

/**
 * Helper for selecting what randomizer to use to create a seed
 */
class RandomizerSelector
{
    /**
     * Get the appropriate randomizer for the specified world.
     *
     * @param \ALttP\World  $world  world to create a randomizer for
     *
     * @return \ALttP\Contracts\Randomizer
     */
    public static function getRandomizer(World $world) {
        if ($world->config('doors.shuffle', 'vanilla') !== 'vanilla'
                || $world->config('overworld.shuffle', 'vanilla') !== 'vanilla'
                || $world->config('overworld.swap', 'vanilla') !== 'vanilla'
                || $world->config('overworld.fluteshuffle', 'vanilla') !== 'vanilla'
                || $world->config('dropShuffle', 'off') === 'on'
                || $world->config('shopsanity', 'off') === 'on'
                || $world->config('entrances', 'none') !== 'none') {
            return new OverworldRandomizer([$world]);
        } else {
            return new Randomizer([$world]);
        }
    }
}
