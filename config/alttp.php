<?php

return [
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
            'HardMode' => 0,
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
                    'ow_keep_similar' => 'off',
                    'ow_swap' => 'vanilla',
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
                    'ow_keep_similar' => 'off',
                    'ow_swap' => 'vanilla',
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
                    'ow_keep_similar' => 'off',
                    'ow_swap' => 'vanilla',
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
                'doors' => [
                    'glitches_required' => 'none',
                    'item_placement' => 'advanced',
                    'dungeon_items' => 'standard',
                    'drop_shuffle' => 'on',
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
                    'ow_keep_similar' => 'off',
                    'ow_swap' => 'vanilla',
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
                    'ow_keep_similar' => 'off',
                    'ow_swap' => 'mixed',
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
                    'ow_keep_similar' => 'on',
                    'ow_swap' => 'vanilla',
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
                    'ow_keep_similar' => 'off',
                    'ow_swap' => 'vanilla',
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
                    'ow_keep_similar' => 'off',
                    'ow_swap' => 'vanilla',
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
                    'ow_keep_similar' => 'off',
                    'ow_swap' => 'vanilla',
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
                    'ow_keep_similar' => 'off',
                    'ow_swap' => 'mixed',
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
                    'accessibility' => 'items',
                    'goal' => 'ganon',
                    'tower_open' => '7',
                    'ganon_open' => '7',
                    'ganon_item' => 'random',
                    'world_state' => 'open',
                    'entrance_shuffle' => 'none',
                    'door_shuffle' => 'vanilla',
                    'door_intensity' => '1',
                    'ow_shuffle' => 'vanilla',
                    'ow_keep_similar' => 'off',
                    'ow_swap' => 'vanilla',
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
                    'ow_keep_similar' => 'off',
                    'ow_swap' => 'vanilla',
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
                    'ow_keep_similar' => 'off',
                    'ow_swap' => 'mixed',
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
                'insanity' => 'Insanity',
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
            'ow_keep_similar' => [
                'off' => 'Off',
                'on' => 'On',
            ],
            'ow_swap' => [
                'vanilla' => 'None',
                'mixed' => 'Mixed',
                'crossed' => 'Crossed',
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
                'off' => 100,
                'on' => 0,
            ],
            'accessibility' => [
                'items' => 60,
                'locations' => 10,
                'none' => 30,
            ],
            'goals' => [
                'ganon' => 30,
                'fast_ganon' => 40,
                'dungeons' => 10,
                'pedestal' => 10,
                'triforce-hunt' => 10,
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
            ],
            'ganon_item' => [
                'default' => 100,
                'random' => 0,
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
                'none' => 92,
                'simple' => 2,
                'restricted' => 2,
                'full' => 2,
                'crossed' => 1,
                'insanity' => 1,
                'dungeonssimple' => 0,
                'dungeonsfull' => 0,
            ],
            'door_shuffle' => [
                'vanilla' => 100,
                'basic' => 0,
                'crossed' => 0,
            ],
            'door_intensity' => [
                '1' => 100,
                '2' => 0,
                '3' => 0,
            ],
            'ow_shuffle' => [
                'vanilla' => 100,
                'parallel' => 0,
                'full' => 0,
            ],
            'ow_keep_similar' => [
                'off' => 100,
                'on' => 0,
            ],
            'ow_swap' => [
                'vanilla' => 100,
                'mixed' => 0,
                'crossed' => 0,
            ],
            'ow_flute_shuffle' => [
                'vanilla' => 100,
                'balanced' => 0,
                'random' => 0,
            ],
            'shopsanity' => [
                'off' => 100,
                'on' => 0,
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
                'randomized' => 70,
                'assured' => 10,
                'vanilla' => 10,
                'swordless' => 10,
                'bombs' => 0,
                'assured_bombs' => 0,
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
