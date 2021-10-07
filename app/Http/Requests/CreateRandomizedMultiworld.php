<?php

namespace ALttP\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use ALttP\Rules\OverrideStartScreenRule;

class CreateRandomizedMultiworld extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @todo have these return better error strings
     *
     * @return array
     */
    public function rules()
    {
        $valid_settings = array_map(function ($group) {
            return array_keys($group);
        }, config('alttp.randomizer.item'));

        return [
            'worlds.*.glitches' => [
                Rule::in($valid_settings['glitches_required']),
            ],
            'worlds.*.item_placement' => [
                Rule::in($valid_settings['item_placement']),
            ],
            'worlds.*.dungeon_items' => [
                Rule::in($valid_settings['dungeon_items']),
            ],
            'worlds.*.drop_shuffle' => [
                Rule::in($valid_settings['drop_shuffle']),
            ],
            'worlds.*.accessibility' => [
                Rule::in($valid_settings['accessibility']),
            ],
            'worlds.*.goal' => [
                Rule::in($valid_settings['goals']),
            ],
            'worlds.*.tower_open' => [
                Rule::in($valid_settings['tower_open']),
            ],
            'worlds.*.ganon_open' => [
                Rule::in($valid_settings['ganon_open']),
            ],
            'worlds.*.ganon_item' => [
                Rule::in($valid_settings['ganon_item']),
            ],
            'worlds.*.mode' => [
                Rule::in($valid_settings['world_state']),
            ],
            'worlds.*.entrance_shuffle' => [
                Rule::in($valid_settings['entrance_shuffle']),
            ],
            'worlds.*.door_shuffle' => [
                Rule::in($valid_settings['door_shuffle']),
            ],
            'worlds.*.door_intensity' => [
                Rule::in($valid_settings['door_intensity']),
            ],
            'worlds.*.ow_shuffle' => [
                Rule::in($valid_settings['ow_shuffle']),
            ],
            'worlds.*.ow_crossed' => [
                Rule::in($valid_settings['ow_crossed']),
            ],
            'worlds.*.ow_keep_similar' => [
                Rule::in($valid_settings['ow_keep_similar']),
            ],
            'worlds.*.ow_mixed' => [
                Rule::in($valid_settings['ow_mixed']),
            ],
            'worlds.*.ow_flute_shuffle' => [
                Rule::in($valid_settings['ow_flute_shuffle']),
            ],
            'worlds.*.shopsanity' => [
                Rule::in($valid_settings['shopsanity']),
            ],
            'worlds.*.boss_shuffle' => [
                Rule::in($valid_settings['boss_shuffle']),
            ],
            'worlds.*.enemy_shuffle' => [
                Rule::in($valid_settings['enemy_shuffle']),
            ],
            'worlds.*.pot_shuffle' => [
                Rule::in($valid_settings['pot_shuffle']),
            ],
            'worlds.*.hints' => [
                Rule::in($valid_settings['hints']),
            ],
            'worlds.*.weapons' => [
                Rule::in($valid_settings['weapons']),
            ],
            'worlds.*.item_pool' => [
                Rule::in($valid_settings['item_pool']),
            ],
            'worlds.*.item_functionality' => [
                Rule::in($valid_settings['item_functionality']),
            ],
            'worlds.*.enemy_damage' => [
                Rule::in($valid_settings['enemy_damage']),
            ],
            'worlds.*.enemy_health' => [
                Rule::in($valid_settings['enemy_health']),
            ],
            'worlds.*.override_start_screen' => [
                new OverrideStartScreenRule
            ]
        ];
    }
}

