<?php
return [
    'loader' => [
        'title' => 'Getting Started',
        'file_select' => 'Select ROM File',
        'content' => '<ol>'
            . '<li>Select your ROM file and load it into the browser (Please use a <strong>Zelda no Densetsu: Kamigami no Triforce v1.0</strong> ROM with an .smc or .sfc extension)</li>'
            . '<li>Select the <a href="/en/options">' . __('navigation.options') . '</a> for how you would like your game randomized</li>'
            . '<li>Click ' . __('randomizer.generate.spoiler_race') . '</li>'
            . '<li>Then Save your ROM and get to playing</li>'
            . '</ol>',
    ],
    'info' => [
        'spoilerwarning' => 'WARNING: The generator of this game viewed the spoiler log.',
        'mystery' => 'This is a mystery game.  The settings must be discovered while you play!',
        'logic' => __('randomizer.glitches_required.title'),
        'accessibility' => __('randomizer.accessibility.title'),
        'build' => 'ROM build',
        'difficulty' => __('randomizer.difficulty.title'),
        'variation' => __('randomizer.variation.title'),
        'shuffle' => __('randomizer.entrance_shuffle.title'),
        'door_shuffle' => __('randomizer.door_shuffle.title'),
        'ow_shuffle' => __('randomizer.ow_shuffle.title'),
        'ow_swap' => __('randomizer.ow_swap.title'),
        'mode' => __('randomizer.world_state.title'),
        'weapons' => __('randomizer.weapons.title'),
        'goal' => __('randomizer.goal.title'),
        'permalink' => 'Permalink',
        'special' => 'Special',
        'notes' => 'Notes',
        'generated' => 'Created',
    ],
    'settings' => [
        'heart_speed' => 'Heart Speed',
        'heart_speeds' => [
            'off' => 'Off',
            'double' => 'Double Speed',
            'normal' => 'Normal Speed',
            'half' => 'Half Speed',
            'quarter' => 'Quarter Speed',
        ],
        'menu_speed' => 'Menu Speed',
        'menu_speeds' => [
            'instant' => 'Instant',
            'fast' => 'Fast',
            'normal' => 'Normal',
            'slow' => 'Slow',
        ],
        'heart_color' => 'Heart Color',
        'heart_colors' => [
            'blue' => 'Blue',
            'green' => 'Green',
            'red' => 'Red',
            'yellow' => 'Yellow',
            'random' => 'Random',
        ],
        'play_as' => 'Play As',
        'sprite_file_select' => 'Select .zspr File',
        'music' => 'Background Music',
        'music_info' => '(set to "No" for <a href="https://alttprlinks.page.link/SjiP" target="_blank" rel="noopener noreferrer">MSU-1 support</a>)',
        'quickswap' => 'Item Quickswap',
        'palette_shuffle' => 'Palette Shuffle',
        'race_warning' => 'Does not work in Race ROMs',
        'reduce_flashing' => 'Reduce Flashing',
        'reduce_flashing_warning' => 'This option only reduces the effects of flashing. Individual photosensitivity to effects may still vary.',
        'fake_boots' => 'Fake Boots',
    ],
];
