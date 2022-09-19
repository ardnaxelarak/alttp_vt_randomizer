<?php

return [
    'banner' => env('BANNER_TEXT', null),
    'mw_host' => env('MW_HOST', null),
    'hostname' => env('HOSTNAME', null),
    'base_rom' => env('ENEMIZER_BASE', null),
    'api_throttle_whitelist' => explode(',', env('API_THROTTLE_WHITELIST', '')),
    'custom' => [
        'prize' => [
            'crossWorld' => false,
            'shufflePendants' => false,
            'shuffleCrystals' => false,
        ],
        'region' => [
            'bossNormalLocation' => false,
            'pyramidBowUpgrade' => false,
            'bossHaveKey' => false,
            'forceSkullWoodsKey' => false,
            'wildKeys' => false,
            'wildBigKeys' => false,
            'wildMaps' => false,
            'wildCompasses' => false,
        ],
        'rom' => [
            'genericKeys' => false,
        ],
        'spoil' => [
            'BootsLocation' => false,
        ],
    ],
    'goals' => [
        'triforce-hunt' => [
            'item' => [
                'count' => [
                    'TriforcePiece' => 30,
                ],
                'Goal' => [
                    'Required' => 20,
                    'Icon' => 'triforce',
                ],
            ],
        ],
        'trinity' => [
            'item' => [
                'count' => [
                    'TriforcePiece' => 10,
                ],
                'Goal' => [
                    'Required' => 8,
                    'Icon' => 'triforce',
                ],
            ],
            'rom' => [
                'mapOnPickup' => true,
            ],
        ],
    ],
    'randomizer' => [
        'item' => [
            'presets' => [
                'custom' => [],
                'beginner' => [
                    'glitches_required' => 'none',
                    'item_placement' => 'basic',
                    'dungeon_items' => 'standard',
                    'drop_shuffle' => 'off',
                    'bonk_shuffle' => 'off',
                    'pottery_shuffle' => 'none',
                    'accessibility' => 'locations',
                    'goal' => 'ganon',
                    'tower_open' => '7',
                    'ganon_open' => '7',
                    'ganon_item' => 'default',
                    'world_state' => 'standard',
                    'entrance_shuffle' => 'none',
                    'door_shuffle' => 'vanilla',
                    'door_intensity' => '1',
                    'ow_shuffle' => 'vanilla',
                    'ow_crossed' => 'vanilla',
                    'ow_keep_similar' => 'off',
                    'ow_mixed' => 'off',
                    'ow_flute_shuffle' => 'vanilla',
                    'shopsanity' => 'off',
                    'boss_shuffle' => 'none',
                    'enemy_shuffle' => 'none',
                    'hints' => 'off',
                    'weapons' => 'assured',
                    'item_pool' => 'normal',
                    'item_functionality' => 'normal',
                    'enemy_damage' => 'default',
                    'enemy_health' => 'default',
                ],
                'default' => [
                    'glitches_required' => 'none',
                    'item_placement' => 'advanced',
                    'dungeon_items' => 'standard',
                    'drop_shuffle' => 'off',
                    'bonk_shuffle' => 'off',
                    'pottery_shuffle' => 'none',
                    'accessibility' => 'items',
                    'goal' => 'ganon',
                    'tower_open' => '7',
                    'ganon_open' => '7',
                    'ganon_item' => 'default',
                    'world_state' => 'open',
                    'entrance_shuffle' => 'none',
                    'door_shuffle' => 'vanilla',
                    'door_intensity' => '1',
                    'ow_shuffle' => 'vanilla',
                    'ow_crossed' => 'vanilla',
                    'ow_keep_similar' => 'off',
                    'ow_mixed' => 'off',
                    'ow_flute_shuffle' => 'vanilla',
                    'shopsanity' => 'off',
                    'boss_shuffle' => 'none',
                    'enemy_shuffle' => 'none',
                    'hints' => 'on',
                    'weapons' => 'randomized',
                    'item_pool' => 'normal',
                    'item_functionality' => 'normal',
                    'enemy_damage' => 'default',
                    'enemy_health' => 'default',
                ],
                'tournament' => [
                    'glitches_required' => 'none',
                    'item_placement' => 'advanced',
                    'dungeon_items' => 'standard',
                    'drop_shuffle' => 'off',
                    'bonk_shuffle' => 'off',
                    'pottery_shuffle' => 'none',
                    'accessibility' => 'items',
                    'goal' => 'ganon',
                    'tower_open' => '7',
                    'ganon_open' => '7',
                    'ganon_item' => 'default',
                    'world_state' => 'standard',
                    'entrance_shuffle' => 'none',
                    'door_shuffle' => 'vanilla',
                    'door_intensity' => '1',
                    'ow_shuffle' => 'vanilla',
                    'ow_crossed' => 'vanilla',
                    'ow_keep_similar' => 'off',
                    'ow_mixed' => 'off',
                    'ow_flute_shuffle' => 'vanilla',
                    'shopsanity' => 'off',
                    'boss_shuffle' => 'none',
                    'enemy_shuffle' => 'none',
                    'hints' => 'on',
                    'weapons' => 'randomized',
                    'item_pool' => 'hard',
                    'item_functionality' => 'normal',
                    'enemy_damage' => 'default',
                    'enemy_health' => 'default',
                ],
                'trinity' => [
                    'glitches_required' => 'none',
                    'item_placement' => 'advanced',
                    'dungeon_items' => 'standard',
                    'drop_shuffle' => 'off',
                    'bonk_shuffle' => 'off',
                    'pottery_shuffle' => 'none',
                    'accessibility' => 'items',
                    'goal' => 'trinity',
                    'tower_open' => 'random',
                    'ganon_open' => 'random',
                    'ganon_item' => 'default',
                    'world_state' => 'open',
                    'entrance_shuffle' => 'none',
                    'door_shuffle' => 'vanilla',
                    'door_intensity' => '1',
                    'ow_shuffle' => 'vanilla',
                    'ow_crossed' => 'vanilla',
                    'ow_keep_similar' => 'off',
                    'ow_mixed' => 'off',
                    'ow_flute_shuffle' => 'vanilla',
                    'shopsanity' => 'off',
                    'boss_shuffle' => 'none',
                    'enemy_shuffle' => 'none',
                    'hints' => 'on',
                    'weapons' => 'randomized',
                    'item_pool' => 'normal',
                    'item_functionality' => 'normal',
                    'enemy_damage' => 'default',
                    'enemy_health' => 'default',
                ],
                'doors' => [
                    'glitches_required' => 'none',
                    'item_placement' => 'advanced',
                    'dungeon_items' => 'standard',
                    'drop_shuffle' => 'on',
                    'bonk_shuffle' => 'off',
                    'pottery_shuffle' => 'none',
                    'accessibility' => 'items',
                    'goal' => 'ganon',
                    'tower_open' => '7',
                    'ganon_open' => '7',
                    'ganon_item' => 'default',
                    'world_state' => 'open',
                    'entrance_shuffle' => 'none',
                    'door_shuffle' => 'crossed',
                    'door_intensity' => '3',
                    'ow_shuffle' => 'vanilla',
                    'ow_crossed' => 'vanilla',
                    'ow_keep_similar' => 'off',
                    'ow_mixed' => 'off',
                    'ow_flute_shuffle' => 'vanilla',
                    'shopsanity' => 'on',
                    'boss_shuffle' => 'none',
                    'enemy_shuffle' => 'none',
                    'hints' => 'on',
                    'weapons' => 'randomized',
                    'item_pool' => 'normal',
                    'item_functionality' => 'normal',
                    'enemy_damage' => 'default',
                    'enemy_health' => 'default',
                ],
                'ow_basic' => [
                    'glitches_required' => 'none',
                    'item_placement' => 'advanced',
                    'dungeon_items' => 'standard',
                    'drop_shuffle' => 'off',
                    'bonk_shuffle' => 'off',
                    'pottery_shuffle' => 'none',
                    'accessibility' => 'items',
                    'goal' => 'ganon',
                    'tower_open' => '7',
                    'ganon_open' => '7',
                    'ganon_item' => 'default',
                    'world_state' => 'open',
                    'entrance_shuffle' => 'none',
                    'door_shuffle' => 'vanilla',
                    'door_intensity' => '3',
                    'ow_shuffle' => 'vanilla',
                    'ow_crossed' => 'vanilla',
                    'ow_keep_similar' => 'off',
                    'ow_mixed' => 'on',
                    'ow_flute_shuffle' => 'balanced',
                    'shopsanity' => 'off',
                    'boss_shuffle' => 'none',
                    'enemy_shuffle' => 'none',
                    'hints' => 'on',
                    'weapons' => 'randomized',
                    'item_pool' => 'normal',
                    'item_functionality' => 'normal',
                    'enemy_damage' => 'default',
                    'enemy_health' => 'default',
                ],
                'ow_advanced' => [
                    'glitches_required' => 'none',
                    'item_placement' => 'advanced',
                    'dungeon_items' => 'standard',
                    'drop_shuffle' => 'off',
                    'bonk_shuffle' => 'off',
                    'pottery_shuffle' => 'none',
                    'accessibility' => 'items',
                    'goal' => 'ganon',
                    'tower_open' => '7',
                    'ganon_open' => '7',
                    'ganon_item' => 'default',
                    'world_state' => 'open',
                    'entrance_shuffle' => 'none',
                    'door_shuffle' => 'vanilla',
                    'door_intensity' => '3',
                    'ow_shuffle' => 'parallel',
                    'ow_crossed' => 'grouped',
                    'ow_keep_similar' => 'on',
                    'ow_mixed' => 'off',
                    'ow_flute_shuffle' => 'balanced',
                    'shopsanity' => 'off',
                    'boss_shuffle' => 'none',
                    'enemy_shuffle' => 'none',
                    'hints' => 'on',
                    'weapons' => 'randomized',
                    'item_pool' => 'normal',
                    'item_functionality' => 'normal',
                    'enemy_damage' => 'default',
                    'enemy_health' => 'default',
                ],
                'veetorp' => [
                    'glitches_required' => 'overworld_glitches',
                    'item_placement' => 'basic',
                    'dungeon_items' => 'standard',
                    'drop_shuffle' => 'off',
                    'bonk_shuffle' => 'off',
                    'pottery_shuffle' => 'none',
                    'accessibility' => 'locations',
                    'goal' => 'fast_ganon',
                    'tower_open' => '7',
                    'ganon_open' => '7',
                    'ganon_item' => 'default',
                    'world_state' => 'open',
                    'entrance_shuffle' => 'none',
                    'door_shuffle' => 'vanilla',
                    'door_intensity' => '1',
                    'ow_shuffle' => 'vanilla',
                    'ow_crossed' => 'vanilla',
                    'ow_keep_similar' => 'off',
                    'ow_mixed' => 'off',
                    'ow_flute_shuffle' => 'vanilla',
                    'shopsanity' => 'off',
                    'boss_shuffle' => 'none',
                    'enemy_shuffle' => 'none',
                    'hints' => 'on',
                    'weapons' => 'randomized',
                    'item_pool' => 'normal',
                    'item_functionality' => 'normal',
                    'enemy_damage' => 'default',
                    'enemy_health' => 'default',
                ],
                'crosskeys' => [
                    'glitches_required' => 'none',
                    'item_placement' => 'advanced',
                    'dungeon_items' => 'full',
                    'drop_shuffle' => 'off',
                    'bonk_shuffle' => 'off',
                    'pottery_shuffle' => 'none',
                    'accessibility' => 'items',
                    'goal' => 'fast_ganon',
                    'tower_open' => '7',
                    'ganon_open' => '7',
                    'ganon_item' => 'default',
                    'world_state' => 'open',
                    'entrance_shuffle' => 'crossed',
                    'door_shuffle' => 'vanilla',
                    'door_intensity' => '1',
                    'ow_shuffle' => 'vanilla',
                    'ow_crossed' => 'vanilla',
                    'ow_keep_similar' => 'off',
                    'ow_mixed' => 'off',
                    'ow_flute_shuffle' => 'vanilla',
                    'shopsanity' => 'off',
                    'boss_shuffle' => 'none',
                    'enemy_shuffle' => 'none',
                    'hints' => 'on',
                    'weapons' => 'randomized',
                    'item_pool' => 'normal',
                    'item_functionality' => 'normal',
                    'enemy_damage' => 'default',
                    'enemy_health' => 'default',
                ],
                'quick' => [
                    'glitches_required' => 'none',
                    'item_placement' => 'basic',
                    'dungeon_items' => 'standard',
                    'drop_shuffle' => 'off',
                    'bonk_shuffle' => 'off',
                    'pottery_shuffle' => 'none',
                    'accessibility' => 'none',
                    'goal' => 'fast_ganon',
                    'tower_open' => '0',
                    'ganon_open' => '0',
                    'ganon_item' => 'default',
                    'world_state' => 'open',
                    'entrance_shuffle' => 'none',
                    'door_shuffle' => 'vanilla',
                    'door_intensity' => '1',
                    'ow_shuffle' => 'vanilla',
                    'ow_crossed' => 'vanilla',
                    'ow_keep_similar' => 'off',
                    'ow_mixed' => 'off',
                    'ow_flute_shuffle' => 'vanilla',
                    'shopsanity' => 'off',
                    'boss_shuffle' => 'none',
                    'enemy_shuffle' => 'none',
                    'hints' => 'off',
                    'weapons' => 'assured',
                    'item_pool' => 'normal',
                    'item_functionality' => 'normal',
                    'enemy_damage' => 'default',
                    'enemy_health' => 'default',
                ],
                'quick_mixed' => [
                    'glitches_required' => 'none',
                    'item_placement' => 'advanced',
                    'dungeon_items' => 'standard',
                    'drop_shuffle' => 'off',
                    'bonk_shuffle' => 'off',
                    'pottery_shuffle' => 'none',
                    'accessibility' => 'none',
                    'goal' => 'fast_ganon',
                    'tower_open' => '0',
                    'ganon_open' => '0',
                    'ganon_item' => 'random',
                    'world_state' => 'open',
                    'entrance_shuffle' => 'none',
                    'door_shuffle' => 'vanilla',
                    'door_intensity' => '1',
                    'ow_shuffle' => 'vanilla',
                    'ow_crossed' => 'vanilla',
                    'ow_keep_similar' => 'off',
                    'ow_mixed' => 'on',
                    'ow_flute_shuffle' => 'balanced',
                    'shopsanity' => 'on',
                    'boss_shuffle' => 'none',
                    'enemy_shuffle' => 'none',
                    'hints' => 'off',
                    'weapons' => 'assured',
                    'item_pool' => 'normal',
                    'item_functionality' => 'normal',
                    'enemy_damage' => 'default',
                    'enemy_health' => 'default',
                ],
                'bombs_only' => [
                    'glitches_required' => 'none',
                    'item_placement' => 'advanced',
                    'dungeon_items' => 'standard',
                    'drop_shuffle' => 'off',
                    'bonk_shuffle' => 'off',
                    'pottery_shuffle' => 'none',
                    'accessibility' => 'items',
                    'goal' => 'ganon',
                    'tower_open' => '7',
                    'ganon_open' => '7',
                    'ganon_item' => 'default',
                    'world_state' => 'open',
                    'entrance_shuffle' => 'none',
                    'door_shuffle' => 'vanilla',
                    'door_intensity' => '1',
                    'ow_shuffle' => 'vanilla',
                    'ow_crossed' => 'vanilla',
                    'ow_keep_similar' => 'off',
                    'ow_mixed' => 'off',
                    'ow_flute_shuffle' => 'vanilla',
                    'shopsanity' => 'off',
                    'boss_shuffle' => 'none',
                    'enemy_shuffle' => 'none',
                    'hints' => 'on',
                    'weapons' => 'bombs',
                    'item_pool' => 'normal',
                    'item_functionality' => 'normal',
                    'enemy_damage' => 'default',
                    'enemy_health' => 'default',
                ],
                'nightmare' => [
                    'glitches_required' => 'none',
                    'item_placement' => 'advanced',
                    'dungeon_items' => 'full',
                    'drop_shuffle' => 'off',
                    'bonk_shuffle' => 'off',
                    'pottery_shuffle' => 'none',
                    'accessibility' => 'none',
                    'goal' => 'ganon',
                    'tower_open' => '7',
                    'ganon_open' => '7',
                    'ganon_item' => 'default',
                    'world_state' => 'inverted',
                    'entrance_shuffle' => 'insanity',
                    'door_shuffle' => 'vanilla',
                    'door_intensity' => '1',
                    'ow_shuffle' => 'vanilla',
                    'ow_crossed' => 'vanilla',
                    'ow_keep_similar' => 'off',
                    'ow_mixed' => 'off',
                    'ow_flute_shuffle' => 'vanilla',
                    'shopsanity' => 'off',
                    'boss_shuffle' => 'random',
                    'enemy_shuffle' => 'random',
                    'hints' => 'off',
                    'weapons' => 'swordless',
                    'item_pool' => 'expert',
                    'item_functionality' => 'expert',
                    'enemy_damage' => 'random',
                    'enemy_health' => 'expert',
                ],
                'everything' => [
                    'glitches_required' => 'none',
                    'item_placement' => 'advanced',
                    'dungeon_items' => 'full',
                    'drop_shuffle' => 'on',
                    'bonk_shuffle' => 'off',
                    'pottery_shuffle' => 'none',
                    'accessibility' => 'items',
                    'goal' => 'fast_ganon',
                    'tower_open' => '7',
                    'ganon_open' => '7',
                    'ganon_item' => 'random',
                    'world_state' => 'open',
                    'entrance_shuffle' => 'crossed',
                    'door_shuffle' => 'crossed',
                    'door_intensity' => '3',
                    'ow_shuffle' => 'full',
                    'ow_crossed' => 'chaos',
                    'ow_keep_similar' => 'off',
                    'ow_mixed' => 'on',
                    'ow_flute_shuffle' => 'random',
                    'shopsanity' => 'on',
                    'boss_shuffle' => 'random',
                    'enemy_shuffle' => 'none',
                    'hints' => 'on',
                    'weapons' => 'randomized',
                    'item_pool' => 'normal',
                    'item_functionality' => 'normal',
                    'enemy_damage' => 'default',
                    'enemy_health' => 'default',
                ],
            ],
            'glitches_required' => [
                'none' => 'None',
                'overworld_glitches' => 'Overworld Glitches',
                'major_glitches' => 'Major Glitches',
                'no_logic' => 'No Logic',
            ],
            'item_placement' => [
                'basic' => 'Basic',
                'advanced' => 'Advanced',
            ],
            'dungeon_items' => [
                'standard' => 'Standard',
                'b' => 'BK Shuffle',
                'mc' => 'MC Shuffle',
                'mcs' => 'MCS Shuffle',
                'full' => 'Full Shuffle',
            ],
            'drop_shuffle' => [
                'on' => 'On',
                'off' => 'Off',
            ],
            'bonk_shuffle' => [
                'on' => 'On',
                'off' => 'Off',
            ],
            'pottery_shuffle' => [
                'none' => 'Vanilla',
                'lottery' => 'Lottery',
                'nonempty' => 'Nonempty',
                'keys' => 'Keys',
                'dungeon' => 'Dungeon',
                'cave' => 'Cave',
                'cavekeys' => 'CaveKeys',
                'reduced' => 'Reduced',
                'clustered' => 'Clustered',
            ],
            'accessibility' => [
                'items' => '100% Inventory',
                'locations' => '100% Locations',
                'none' => 'Not Guaranteed',
            ],
            'goals' => [
                'ganon' => 'Defeat Ganon',
                'fast_ganon' => 'Fast Ganon',
                'dungeons' => 'All Dungeons',
                'pedestal' => 'Master Sword Pedestal',
                'triforce-hunt' => 'Triforce Pieces',
                'trinity' => 'Trinity',
            ],
            'tower_open' => [
                '0' => 'none',
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                '6' => '6',
                '7' => '7',
                'random' => 'random',
            ],
            'ganon_open' => [
                '0' => 'none',
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                '6' => '6',
                '7' => '7',
                'random' => 'random',
                'low_random' => 'low random',
            ],
            'ganon_item' => [
                'default' => 'Default',
                'random' => 'Random',
                'arrow' => 'Silver Arrows',
                'boomerang' => 'Boomerang',
                'hookshot' => 'Hookshot',
                'bomb' => 'Bombs',
                'powder' => 'Magic Powder',
                'fire_rod' => 'Fire Rod',
                'ice_rod' => 'Ice Rod',
                'bombos' => 'Bombos',
                'ether' => 'Ether',
                'quake' => 'Quake',
                'hammer' => 'Hammer',
                'bee' => 'Bee',
                'somaria' => 'Cane of Somaria',
                'byrna' => 'Cane of Byrna',
            ],
            'world_state' => [
                'standard' => 'Standard',
                'open' => 'Open',
                'inverted' => 'Inverted',
                'retro' => 'Retro',
            ],
            'entrance_shuffle' => [
                'none' => 'None',
                'simple' => 'Simple',
                'restricted' => 'Restricted',
                'full' => 'Full',
                'crossed' => 'Crossed',
                'insanity' => 'Non-Reflexive',
                'lite' => 'Lite',
                'lean' => 'Lean',
                'dungeonssimple' => 'Simple (Dungeons Only)',
                'dungeonsfull' => 'Full (Dungeons Only)',
            ],
            'door_shuffle' => [
                'vanilla' => 'None',
                'basic' => 'Basic',
                'crossed' => 'Crossed',
            ],
            'door_intensity' => [
                '1' => '1',
                '2' => '2',
                '3' => '3',
            ],
            'ow_shuffle' => [
                'vanilla' => 'None',
                'parallel' => 'Parallel',
                'full' => 'Full',
            ],
            'ow_crossed' => [
                'vanilla' => 'None',
                'polar' => 'Polar',
                'grouped' => 'Grouped',
                'limited' => 'Limited',
                'chaos' => 'Chaos',
            ],
            'ow_keep_similar' => [
                'off' => 'Off',
                'on' => 'On',
            ],
            'ow_mixed' => [
                'off' => 'Off',
                'on' => 'On',
            ],
            'ow_flute_shuffle' => [
                'vanilla' => 'None',
                'balanced' => 'Balanced',
                'random' => 'Random',
            ],
            'shopsanity' => [
                'off' => 'Off',
                'on' => 'On',
            ],
            'boss_shuffle' => [
                'none' => 'None',
                'simple' => 'Simple',
                'full' => 'Full',
                'random' => 'Random',
            ],
            'enemy_shuffle' => [
                'none' => 'None',
                'shuffled' => 'Shuffled',
                'random' => 'Random',
                'moldorms' => 'Moldorms',
            ],
            'pot_shuffle' => [
                'on' => 'On',
                'off' => 'Off',
            ],
            'hints' => [
                'on' => 'On',
                'off' => 'Off',
            ],
            'weapons' => [
                'randomized' => 'Randomized',
                'assured' => 'Assured',
                'vanilla' => 'Vanilla',
                'swordless' => 'Swordless',
                'bombs' => 'Bomb-Only',
                'assured_bombs' => 'Assured Bomb-Only',
                'byrna' => 'Byrna-Only',
                'assured_byrna' => 'Assured Byrna-Only',
                'somaria' => 'Somaria-Only',
                'assured_somaria' => 'Assured Somaria-Only',
                'cane' => 'Canes-Only',
                'pseudo' => 'Pseudo-Swords',
                'assured_pseudo' => 'Assured Pseudo-Swords',
                'bees' => 'Bee Mode',
            ],
            'item_pool' => [
                'normal' => 'Normal',
                'hard' => 'Hard',
                'expert' => 'Expert',
                'crowd_control' => 'Crowd Control',
            ],
            'item_functionality' => [
                'normal' => 'Normal',
                'hard' => 'Hard',
                'expert' => 'Expert',
            ],
            'enemy_damage' => [
                'default' => 'Default',
                'shuffled' => 'Shuffled',
                'random' => 'Random',
            ],
            'enemy_health' => [
                'default' => 'Default',
                'easy' => 'Easy',
                'hard' => 'Hard',
                'expert' => 'Expert',
            ],
            'spoilers' => [
                'on' => 'On',
                'off' => 'Off',
                'generate' => 'Generate',
                'mystery' => 'Mystery',
            ],
        ],
        'daily_weights' => [
            'glitches_required' => [
                'none' => 88,
                'overworld_glitches' => 10,
                'major_glitches' => 2,
                'no_logic' => 0,
            ],
            'item_placement' => [
                'basic' => 60,
                'advanced' => 40,
            ],
            'dungeon_items' => [
                'standard' => 60,
                'b' => 0,
                'mc' => 10,
                'mcs' => 10,
                'full' => 20,
            ],
            'drop_shuffle' => [
                'off' => 90,
                'on' => 10,
            ],
            'bonk_shuffle' => [
                'off' => 90,
                'on' => 10,
            ],
            'pottery_shuffle' => [
                'none' => 90,
                'lottery' => 2,
                'nonempty' => 2,
                'keys' => 2,
                'dungeon' => 2,
                'cave' => 0,
                'cavekeys' => 2,
                'reduced' => 0,
                'clustered' => 0,
            ],
            'accessibility' => [
                'items' => 60,
                'locations' => 10,
                'none' => 30,
            ],
            'goals' => [
                'ganon' => 25,
                'fast_ganon' => 35,
                'dungeons' => 10,
                'pedestal' => 10,
                'triforce-hunt' => 10,
                'trinity' => 10,
            ],
            'tower_open' => [
                '0' => 5,
                '1' => 5,
                '2' => 5,
                '3' => 5,
                '4' => 5,
                '5' => 5,
                '6' => 5,
                '7' => 50,
                'random' => 15,
            ],
            'ganon_open' => [
                '0' => 5,
                '1' => 5,
                '2' => 5,
                '3' => 5,
                '4' => 5,
                '5' => 5,
                '6' => 5,
                '7' => 50,
                'random' => 15,
                'low_random' => 0,
            ],
            'ganon_item' => [
                'default' => 90,
                'random' => 10,
                'arrow' => 0,
                'boomerang' => 0,
                'hookshot' => 0,
                'bomb' => 0,
                'powder' => 0,
                'fire_rod' => 0,
                'ice_rod' => 0,
                'bombos' => 0,
                'ether' => 0,
                'quake' => 0,
                'hammer' => 0,
                'bee' => 0,
                'somaria' => 0,
                'byrna' => 0,
            ],
            'world_state' => [
                'standard' => 20,
                'open' => 45,
                'inverted' => 25,
                'retro' => 10,
            ],
            'entrance_shuffle' => [
                'none' => 84,
                'simple' => 2,
                'restricted' => 2,
                'full' => 2,
                'crossed' => 1,
                'insanity' => 1,
                'lite' => 2,
                'lean' => 2,
                'dungeonssimple' => 2,
                'dungeonsfull' => 2,
            ],
            'door_shuffle' => [
                'vanilla' => 95,
                'basic' => 3,
                'crossed' => 2,
            ],
            'door_intensity' => [
                '1' => 30,
                '2' => 30,
                '3' => 40,
            ],
            'ow_shuffle' => [
                'vanilla' => 97,
                'parallel' => 2,
                'full' => 1,
            ],
            'ow_crossed' => [
                'vanilla' => 95,
                'polar' => 0,
                'grouped' => 2,
                'limited' => 2,
                'chaos' => 1,
            ],
            'ow_keep_similar' => [
                'off' => 30,
                'on' => 70,
            ],
            'ow_mixed' => [
                'off' => 95,
                'on' => 5,
            ],
            'ow_flute_shuffle' => [
                'vanilla' => 80,
                'balanced' => 15,
                'random' => 5,
            ],
            'shopsanity' => [
                'off' => 90,
                'on' => 10,
            ],
            'boss_shuffle' => [
                'none' => 80,
                'simple' => 5,
                'full' => 5,
                'random' => 10,
            ],
            'enemy_shuffle' => [
                'none' => 90,
                'shuffled' => 7,
                'random' => 3,
            ],
            'hints' => [
                'on' => 50,
                'off' => 50,
            ],
            'weapons' => [
                'randomized' => 56,
                'assured' => 10,
                'vanilla' => 10,
                'swordless' => 3,
                'bombs' => 3,
                'assured_bombs' => 3,
                'byrna' => 2,
                'assured_byrna' => 0,
                'somaria' => 2,
                'assured_somaria' => 0,
                'cane' => 4,
                'pseudo' => 4,
                'assured_pseudo' => 3,
                'bees' => 0,
            ],
            'item_pool' => [
                'normal' => 70,
                'hard' => 20,
                'expert' => 10,
            ],
            'item_functionality' => [
                'normal' => 70,
                'hard' => 20,
                'expert' => 10,
            ],
            'enemy_damage' => [
                'default' => 93,
                'shuffled' => 5,
                'random' => 2,
            ],
            'enemy_health' => [
                'easy' => 2,
                'default' => 92,
                'hard' => 5,
                'expert' => 1,
            ],
            'spoilers' => [
                'on' => 0,
                'off' => 95,
                'generate' => 0,
                'mystery' => 5
            ]
        ],
        'ganon_item_weights' => [
            'default' => 2,
            'arrow' => 7,
            'boomerang' => 7,
            'hookshot' => 7,
            'bomb' => 7,
            'powder' => 7,
            'fire_rod' => 7,
            'ice_rod' => 7,
            'bombos' => 7,
            'ether' => 7,
            'quake' => 7,
            'hammer' => 7,
            'bee' => 7,
            'somaria' => 7,
            'byrna' => 7,
        ],
    ],
];
