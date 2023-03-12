<?php

namespace ALttP\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

/**
 * Helper for rolling mystery seeds
 */
class MysteryRoller
{
    private static function updateSettings(array &$settings, $weights, string $settingsKey) {
        if (!is_array($weights)) {
            Arr::set($settings, $settingsKey, $weights);
            return;
        }

        $keys = array_keys($weights);
        $combined = array_combine($keys, $keys);

        $selected = head(weighted_random_pick($combined, $weights));
        if ($selected != 'no_change') {
            Arr::set($settings, $settingsKey, $selected);
        }
    }

    private static function selectModifier(array $modifier): array {
        $keys = array_keys($modifier);
        $combined = array_combine($keys, $keys);
        $weights = array_combine($keys, array_map(fn($key) => $modifier[$key]['weight'], $keys));

        $selected = head(weighted_random_pick($combined, $weights));
        return $modifier[$selected];
    }

    /**
     * Rolls mystery settings given an array of weights
     *
     * @param \ALttP\World  $world  world to create a randomizer for
     *
     * @return array  Settings for the world
     */
    public static function getSettings(array $weights) : array {
        $settings = ["spoilers" => "mystery"];

        $base = $weights['base_settings'];

        foreach (config('mystery.weight_names') as $key => $value) {
            if (array_key_exists($key, $base)) {
                MysteryRoller::updateSettings($settings, $base[$key], $value);
            }
        }

        foreach ($weights['modifiers'] as $modifierName => $modifierWeights) {
            $subWeights = MysteryRoller::selectModifier($modifierWeights);
            foreach (config('mystery.weight_names') as $key => $value) {
                if (array_key_exists($key, $subWeights)) {
                    MysteryRoller::updateSettings($settings, $subWeights[$key], $value);
                }
            }
        }

        return $settings;
    }
}
