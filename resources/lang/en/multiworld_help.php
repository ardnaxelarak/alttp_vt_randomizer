<?php
return [
    'header' => 'Multiworld Setup',
    'subheader' => 'There are many ways to play ALttP:Randomizer with friends!',
    'cards' => [
        'setup' => [
            'header' => '0. Setup Software Requirements',
            'content' => [
                'Each player should ensure that they have an emulator capable of running Lua Scripts (Bizhawk or Snes9x-rr) or the sd2snes/fkpakpro cartridge connected to their computer.',
                'Each player will need to download and extract the compressed .7z file from the latest release of <a href="https://github.com/Skarsnik/QUsb2snes/releases">QUsb2snes</a>.',
                'Each player will need to download and extract the .7z archive of the MultiClient <a href="/MultiClient.7z">here</a>.',
                'If needed, a program capable of extracting .7z files can be found at <a href="https://www.7-zip.org/">7-zip.org</a>.',
            ]
        ],
        'generation' => [
            'header' => '1. Generate the Multiworld',
            'content' => [
                'Head on over to <a href="/multiworld" target="_blank" rel="noopener noreferrer">' . __('navigation.multiworld') . '</a> and select the options for each player who will be participating in the multiworld. Then click “' . __('multiworld.generate') . '” and you’ll be given a randomized multiworld!',
                'Please note that multiworld generation can take upwards of several minutes, depending on the settings chosen; please be patient.',
            ]
        ],
        'hosting' => [
            'header' => '2. Host the Multidata on a Server',
            'content' => [
                'Handling hosting multiworld servers on this website is still a work in progress, and subject to change.',
                'On the multiworld seed page generated from step 1, click “' . __('multiworld.host') . '”; this will give you a game token and a server address, and open a server management page. Make a note of the server address for later.',
                'The server management page can be used to kick a connection if you encounter any errors around a seat already being filled.',
            ]
        ],
        'downloading' => [
            'header' => '3. Download the ROMs',
            'content' => [
                'Whoever generated the multiworld seed should share the link to the multiworld generated in step 1 with everyone taking part in the game.',
                'Each player should click on the link next to their name to be taken to a page for their individual ROM, which they can customize (choosing their sprite, heart color, etc) and download.',
            ]
        ],
        'loading' => [
            'header' => '4. Load the ROMs and connect to QUsb2Snes',
            'content' => [
                'Each player should run QUsb2Snes.exe from the extracted download from step 0.',
                'The first time QUsb2Snes is run on a computer, it should prompt for how to connect to your game. If you are using Bizhawk or Snes9x-rr, choose the Lua Bridge option. If you are using sd2snes/fxpakpro, choose the sd2snes option.',
                'Players using Bizhawk should check that their SNES core is set to BSNES and their Lua Core is set to Lua+LuaInterface. If you encounter errors later that reference NLua, try changing the Lua Core to NLua, closing the settings window, and reopening it and changing it back. Some people have observed issues with Bizhawk incorrectly being set to NLua despite showing Lua+LuaInterface.',
                'Each player should open their ROM.',
                'Players using Bizhawk or Snes9x-rr should load the “luabridge.lua” script from the LuaBridge of the QUsb2Snes download. Players running on console do not need to take this step.',
            ]
        ],
        'connecting' => [
            'header' => '5. Connect to the Server',
            'content' => [
                'Each player should run MultiClient.exe (from the extraction of MultiClient.7z downloaded in step 0), which should automatically connect to QUsb2Snes. If it indicates a problem connecting to your game, check that QUsb2Snes is running properly and connected by right-clicking on its icon in the taskbar, and ensure that the lua script (if applicable) is running correctly.',
                'MultiClient.exe will prompt for a server address to connect to; each player should enter the server address created when “' . __('multiworld.host') . '” was clicked in step 2. If successful, it should show a message indicating you have successfully connected to the game.',
            ]
        ],
        'playing' => [
            'header' => '6. Play the Game!',
            'content' => [
                'At this point, everything should be set up and no further configuration should be necessary.',
                'If for some reason your MultiClient loses connection to your ROM, you can type “/snes” into MultiClient to reconnect.',
                'If your MultiClient loses connection to the server, you can type “/connect” into MultiClient to reconnect.',
                'To forfeit your seat and give all players all remaining items from your world, you can type “!forfeit” into MultiClient.',
            ]
        ],
    ],
];

