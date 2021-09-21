<?php
return [
    'title' => 'Randomizer',
    'preset' => [
        'title' => 'Select Preset',
        'customize' => 'Customize',
        'options' => [
            'default' => 'Default',
            'bombs_only' => 'Bomb-Only Mode',
            'doors' => 'Dungeon Shuffle',
            'ow_basic' => 'Basic Overworld Shuffle',
            'ow_advanced' => 'Advanced Overworld Shuffle',
            'beginner' => 'Beginner',
            'veetorp' => 'OWG (Veetorp’s Favorite)',
            'crosskeys' => 'Crosskeys',
            'quick' => 'Super Quick',
            'quick_mixed' => 'Super Quick Mixed',
            'nightmare' => 'Nightmare',
            'tournament' => 'Tournament',
            'everything' => 'Everything Shuffle',
            'custom' => 'Custom',
        ],
    ],
    'placement' => [
        'title' => 'Item Placement',
    ],
    'item_placement' => [
        'title' => 'Item Placement',
        'options' => [
            'basic' => 'Basic',
            'advanced' => 'Advanced',
        ],
    ],
    'dungeon_items' => [
        'title' => 'Dungeon Item Shuffle',
        'options' => [
            'standard' => 'Standard',
            'b' => 'Big Keys',
            'mc' => 'Maps/Compasses',
            'mcs' => 'Maps/Compasses/Small Keys',
            'full' => 'Keysanity',
        ],
    ],
    'drop_shuffle' => [
        'title' => 'Drop Shuffle',
        'options' => [
            'on' => 'On',
            'off' => 'Off',
        ],
    ],
    'accessibility' => [
        'title' => 'Accessibility',
        'options' => [
            'items' => '100% Inventory',
            'locations' => '100% Locations',
            'none' => 'Beatable',
        ],
    ],
    'glitches_required' => [
        'title' => 'Glitches Required',
        'options' => [
            'none' => 'None',
            'overworld_glitches' => 'Overworld Glitches',
            'major_glitches' => 'Major Glitches',
            'no_logic' => 'No Logic',
        ],
        'glitch_warning' => 'These settings require knowledge of Major Glitches<sup>**</sup>',
    ],
    'goal' => [
        'title' => 'Goal',
        'options' => [
            'ganon' => 'Defeat Ganon',
            'fast_ganon' => 'Fast Ganon',
            'dungeons' => 'All Dungeons',
            'pedestal' => 'Master Sword Pedestal',
            'triforce-hunt' => 'Triforce Pieces',
        ],
    ],
    'tower_open' => [
        'title' => 'Open Tower',
        'options' => [
            '0' => '0 Crystals',
            '1' => '1 Crystal',
            '2' => '2 Crystals',
            '3' => '3 Crystals',
            '4' => '4 Crystals',
            '5' => '5 Crystals',
            '6' => '6 Crystals',
            '7' => '7 Crystals',
            'random' => 'Random'
        ],
    ],
    'ganon_open' => [
        'title' => 'Ganon Vulnerable',
        'options' => [
            '0' => '0 Crystals',
            '1' => '1 Crystal',
            '2' => '2 Crystals',
            '3' => '3 Crystals',
            '4' => '4 Crystals',
            '5' => '5 Crystals',
            '6' => '6 Crystals',
            '7' => '7 Crystals',
            'random' => 'Random'
        ],
    ],
    'ganon_item' => [
        'title' => 'Ganon Vulnerability Item',
        'options' => [
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
            'byrna' => 'Cane of Byrna'
        ],
    ],
    'gameplay' => [
        'title' => 'Gameplay',
    ],
    "world_state" => [
        'title' => 'World State',
        'options' => [
            'standard' => 'Standard',
            'open' => 'Open',
            'inverted' => 'Inverted',
            'retro' => 'Retro',
        ],
    ],
    "entrance_shuffle" => [
        'title' => 'Entrance Shuffle',
        'options' => [
            'none' => 'None',
            'simple' => 'Simple',
            'restricted' => 'Restricted',
            'full' => 'Full',
            'crossed' => 'Crossed',
            'insanity' => 'Insanity',
            'dungeonssimple' => 'Simple (Dungeons Only)',
            'dungeonsfull' => 'Full (Dungeons Only)',
        ],
    ],
    'doors_warning' => 'Seeds with door shuffle or drop shuffle enabled could take a long time to generate and may sometimes fail, but should succeed after a retry or two if necessary.',
    "door_shuffle" => [
        'title' => 'Door Shuffle',
        'options' => [
            'vanilla' => 'None',
            'basic' => 'Basic',
            'crossed' => 'Crossed',
        ],
    ],
    "door_intensity" => [
        'title' => 'Door Shuffle Intensity',
        'options' => [
            '1' => '1: Normal',
            '2' => '2: Open Edges',
            '3' => '3: Lobbies',
        ],
    ],
    "ow_shuffle" => [
        'title' => 'Overworld Layout Shuffle',
        'options' => [
            'vanilla' => 'None',
            'parallel' => 'Parallel',
            'full' => 'Full',
        ],
    ],
    "ow_crossed" => [
        'title' => 'Overworld Crossed',
        'options' => [
            'vanilla' => 'None',
            'polar' => 'Polar',
            'grouped' => 'Grouped',
            'limited' => 'Limited',
            'chaos' => 'Chaos',
        ],
    ],
    "ow_keep_similar" => [
        'title' => 'Overworld Keep Similar',
        'options' => [
            'on' => 'On',
            'off' => 'Off',
        ],
    ],
    "ow_mixed" => [
        'title' => 'Overworld Tile Swap',
        'options' => [
            'off' => 'None',
            'on' => 'Mixed',
        ],
    ],
    "ow_flute_shuffle" => [
        'title' => 'Flute Shuffle',
        'options' => [
            'vanilla' => 'None',
            'balanced' => 'Balanced',
            'random' => 'Random',
        ],
    ],
    "shopsanity" => [
        'title' => 'Shopsanity',
        'options' => [
            'on' => 'On',
            'off' => 'Off',
        ],
    ],
    "boss_shuffle" => [
        'title' => 'Boss Shuffle',
        'options' => [
            'none' => 'None',
            'simple' => 'Simple',
            'full' => 'Full',
            'random' => 'Random',
        ],
    ],
    "enemy_shuffle" => [
        'title' => 'Enemy Shuffle',
        'options' => [
            'none' => 'None',
            'shuffled' => 'Shuffled',
            'random' => 'Random',
        ],
    ],
    "hints" => [
        'title' => 'Hints',
        'options' => [
            'on' => 'On',
            'off' => 'Off',
        ],
    ],
    'weapons' => [
        'title' => 'Swords',
        'options' => [
            'randomized' => 'Randomized',
            'assured' => 'Assured',
            'vanilla' => 'Vanilla',
            'swordless' => 'Swordless',
            'bombs' => 'Bomb-Only',
            'assured_bombs' => 'Bomb-Only (Starting Bombs)',
            'pseudo' => 'Pseudo-Swords',
            'assured_pseudo' => 'Assured Pseudo-Swords',
        ],
        'bombs_warning' => 'Bomb-Only mode is still in development, but should be stable.',
    ],
    'item_pool' => [
        'title' => 'Item Pool',
        'options' => [
            'easy' => 'Easy',
            'normal' => 'Normal',
            'hard' => 'Hard',
            'expert' => 'Expert',
            'crowd_control' => 'Crowd Control',
        ],
        'crowd_control_warning' => '<sup>*</sup> This setting is meant to be used with the Crowd Control Twitch extension. find out more: <a href="https://crowdcontrol.live/" target="_blank" rel=”noopener noreferrer”>https://crowdcontrol.live/</a>',
    ],
    'item_functionality' => [
        'title' => 'Item Functionality',
        'options' => [
            'easy' => 'Easy',
            'normal' => 'Normal',
            'hard' => 'Hard',
            'expert' => 'Expert',
        ],
    ],
    'enemy_damage' => [
        'title' => 'Enemy Damage',
        'options' => [
            'default' => 'Default',
            'shuffled' => 'Shuffled',
            'random' => 'Random',
        ],
    ],
    'enemy_health' => [
        'title' => 'Enemy Health',
        'options' => [
            'default' => 'Default',
            'easy' => 'Easy',
            'hard' => 'Hard',
            'expert' => 'Expert',
        ],
    ],
    'spoiler' => [
        'title' => 'Spoilers',
        'options' => [
            'off' => 'Disabled',
            'on' => 'Enabled',
            'generate' => 'Only on Generate',
            'mystery' => 'Mystery (settings hidden)'
        ],
    ],
    'generate' => [
        'race' => 'Generate Race ROM',
        'race_warning' => 'Spoilers will <span class="running-now">never</span> be available for this option.',
        'spoiler_race' => 'Generate Normal ROM',
        'casual' => 'Generate ROM',
        'back' => 'Change Settings',
        'forward' => 'View Generated Game',
        'regenerate' => 'Generate Again',
        'regenerate_tooltip' => 'Generate new game with same settings',
        'generating' => 'Generating...',
    ],
    'details' => [
        'title' => 'Game Details',
        'save_spoiler' => 'Save Spoiler',
        'save_rom' => 'Save ROM',
    ],
    // deprecated
    'variation' => [
        'title' => 'Variation',
    ],
    'difficulty' => [
        'title' => 'Difficulty',
        'options' => [
            'easy' => 'Easy',
            'normal' => 'Normal',
            'hard' => 'Hard',
            'expert' => 'Expert',
            'insane' => 'Insane',
            'crowdControl' => 'Crowd Control',
        ],
    ],
];
