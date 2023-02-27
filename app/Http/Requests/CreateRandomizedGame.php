<?php

namespace ALttP\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use ALttP\Rules\OverrideStartScreenRule;

class CreateRandomizedGame extends FormRequest
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
            'glitches' => [
                Rule::in($valid_settings['glitches_required']),
            ],
            'item_placement' => [
                Rule::in($valid_settings['item_placement']),
            ],
            'dungeon_items' => [
                Rule::in($valid_settings['dungeon_items']),
            ],
            'boss_items' => [
                Rule::in($valid_settings['boss_items']),
            ],
            'drop_shuffle' => [
                Rule::in($valid_settings['drop_shuffle']),
            ],
            'bonk_shuffle' => [
                Rule::in($valid_settings['bonk_shuffle']),
            ],
            'pottery_shuffle' => [
                Rule::in($valid_settings['pottery_shuffle']),
            ],
            'accessibility' => [
                Rule::in($valid_settings['accessibility']),
            ],
            'goal' => [
                Rule::in($valid_settings['goals']),
            ],
            'crystals.tower' => [
                Rule::in($valid_settings['tower_open']),
            ],
            'crystals.ganon' => [
                Rule::in($valid_settings['ganon_open']),
            ],
            'ganon_item' => [
                Rule::in($valid_settings['ganon_item']),
            ],
            'mode' => [
                Rule::in($valid_settings['world_state']),
            ],
            'entrances' => [
                Rule::in($valid_settings['entrance_shuffle']),
            ],
            'doors.shuffle' => [
                Rule::in($valid_settings['door_shuffle']),
            ],
            'doors.intensity' => [
                Rule::in($valid_settings['door_intensity']),
            ],
            'doors.type_mode' => [
                Rule::in($valid_settings['door_type_mode']),
            ],
            'doors.trap_mode' => [
                Rule::in($valid_settings['trap_door_mode']),
            ],
            'doors.decoupled' => [
                Rule::in($valid_settings['decouple_doors']),
            ],
            'overworld.shuffle' => [
                Rule::in($valid_settings['ow_shuffle']),
            ],
            'overworld.crossed' => [
                Rule::in($valid_settings['ow_crossed']),
            ],
            'overworld.keepSimilar' => [
                Rule::in($valid_settings['ow_keep_similar']),
            ],
            'overworld.mixed' => [
                Rule::in($valid_settings['ow_mixed']),
            ],
            'overworld.fluteShuffle' => [
                Rule::in($valid_settings['ow_flute_shuffle']),
            ],
            'overworld.whirlpoolShuffle' => [
                Rule::in($valid_settings['ow_whirlpool_shuffle']),
            ],
            'shopsanity' => [
                Rule::in($valid_settings['shopsanity']),
            ],
            'enemizer.boss_shuffle' => [
                Rule::in($valid_settings['boss_shuffle']),
            ],
            'enemizer.enemy_shuffle' => [
                Rule::in($valid_settings['enemy_shuffle']),
            ],
            'enemizer.pot_shuffle' => [
                Rule::in($valid_settings['pot_shuffle']),
            ],
            'hints' => [
                Rule::in($valid_settings['hints']),
            ],
            'weapons' => [
                Rule::in($valid_settings['weapons']),
            ],
            'item.pool' => [
                Rule::in($valid_settings['item_pool']),
            ],
            'item.functionality' => [
                Rule::in($valid_settings['item_functionality']),
            ],
            'equipment.flute' => [
                Rule::in($valid_settings['starting_flute']),
            ],
            'equipment.boots' => [
                Rule::in($valid_settings['starting_boots']),
            ],
            'enemizer.enemy_damage' => [
                Rule::in($valid_settings['enemy_damage']),
            ],
            'enemizer.enemy_health' => [
                Rule::in($valid_settings['enemy_health']),
            ],
            'override_start_screen' => [
                new OverrideStartScreenRule
            ]
        ];
    }
}
