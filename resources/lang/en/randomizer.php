<?php
return [
    'title' => 'Randomizer',
    'preset' => [
        'title' => 'Select Preset',
        'customize' => 'Customize',
        'options' => [
            'default' => 'Default',
            'trinity' => 'Trinity',
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
            'full' => 'Full',
        ],
    ],
    'boss_items' => [
        'title' => 'Boss Items',
        'options' => [
            'any' => 'No Restriction',
            'nondungeon' => 'No Dungeon Items',
        ],
    ],
    'drop_shuffle' => [
        'title' => 'Enemy Drop Shuffle',
        'options' => [
            'on' => 'On',
            'off' => 'Off',
        ],
    ],
    'bonk_shuffle' => [
        'title' => 'Bonk Shuffle',
        'options' => [
            'on' => 'On',
            'off' => 'Off',
        ],
    ],
    'pottery_shuffle' => [
        'title' => 'Pottery Shuffle',
        'options' => [
            'none' => 'Vanilla',
            'lottery' => 'Lottery',
            'nonempty' => 'Non-Empty',
            'keys' => 'Keys',
            'dungeon' => 'Dungeon',
            'cave' => 'Cave',
            'cavekeys' => 'Caves + Keys',
            'reduced' => 'Reduced',
            'clustered' => 'Clustered',
        ],
    ],
    'accessibility' => [
        'title' => 'Accessibility',
        'options' => [
            'items' => '100% Inventory',
            'locations' => '100% Locations',
            'none' => 'Beatable Only',
        ],
    ],
    'glitches_required' => [
        'title' => 'Glitches Required',
        'options' => [
            'none' => 'None',
            'overworld_glitches' => 'Overworld Glitches',
            'hybrid_major_glitches' => 'Hybrid Major Glitches',
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
            'trinity' => 'Trinity',
            'ambroz1a' => 'Ambroz1a',
            'ganonhunt' => 'Ganonhunt',
            'completionist' => 'Completionist',
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
            'random' => 'Random',
            'low_random' => 'Low Random (1-4)'
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
    'overworld_shuffle' => [
        'title' => 'Overworld Shuffle',
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
            'insanity' => 'Non-Reflexive',
            'lite' => 'Lite',
            'lean' => 'Lean',
            'dungeonssimple' => 'Simple (Dungeons Only)',
            'dungeonsfull' => 'Full (Dungeons Only)',
        ],
    ],
    'doors_warning' => 'Seeds with door shuffle or drop shuffle enabled could take a long time to generate and may sometimes fail, but should succeed after a retry or two if necessary.',
    "door_shuffle" => [
        'title' => 'Door Shuffle',
        'options' => [
            'vanilla' => 'None',
            'partitioned' => 'Partitioned',
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
    'door_type_mode' => [
        'title' => 'Door Shuffle Type Mode',
        'options' => [
            'original' => 'Original',
            'big' => 'Big',
            'all' => 'All',
            'chaos' => 'Chaos',
        ],
    ],
    'trap_door_mode' => [
        'title' => 'Trap Door Mode',
        'options' => [
            'vanilla' => 'Vanilla',
            'optional' => 'Optional',
            'boss' => 'Boss',
            'oneway' => 'One-Way',
        ],
    ],
    'decouple_doors' => [
        'title' => 'Decouple Doors',
        'options' => [
            'on' => 'On',
            'off' => 'Off',
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
        'title' => 'Overworld Tile Flip',
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
    "ow_whirlpool_shuffle" => [
        'title' => 'Whirlpool Shuffle',
        'options' => [
            'vanilla' => 'None',
            'shuffled' => 'Shuffled',
        ],
    ],
    "shopsanity" => [
        'title' => 'Shop Shuffle',
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
            'moldorm' => 'All Moldorm',
        ],
    ],
    "enemy_shuffle" => [
        'title' => 'Enemy Shuffle',
        'options' => [
            'none' => 'None',
            'shuffled' => 'Shuffled',
            'killable' => 'Killable',
            'random' => 'Random',
            'moldorms' => 'Moldorms',
        ],
        'moldorm_warning' => 'Are you sure you really want this?',
        'killable_warning' => 'Killable Enemy Shuffle is still under development; expect occasional graphics irregularities.',
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
            'swordless_hammer' => 'Swordless w/ Hammer on B',
            'bombs' => 'Bomb-Only',
            'assured_bombs' => 'Bomb-Only (Starting Bombs)',
            'byrna' => 'Byrna-Only',
            'assured_byrna' => 'Byrna-Only (Starting Cane)',
            'somaria' => 'Somaria-Only',
            'assured_somaria' => 'Somaria-Only (Starting Cane)',
            'cane' => 'Canes-Only',
            'pseudo' => 'Pseudo-Swords',
            'assured_pseudo' => 'Assured Pseudo-Swords',
            'bees' => 'Bee Mode',
            'bugnet' => 'Bug-Net-Only',
            'assured_bugnet' => 'Bug-Net-Only (Starting Bug Net)',
        ],
        'bees_warning' => 'Bee Mode is only partially implemented, and not compatible with entrance, door, or overworld shuffle.',
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
    'starting_flute' => [
        'title' => 'Starting Flute',
        'options' => [
            'off' => 'Off',
            'starting' => 'Starting Flute (Pre-Activated)',
        ],
    ],
    'starting_boots' => [
        'title' => 'Starting Boots',
        'options' => [
            'off' => 'Off',
            'pseudo' => 'Pseudoboots',
            'starting' => 'Starting Boots',
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
        'slow_warning' => 'Generation of seeds with complicated settings may take several minutes. Please be patient.',
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
