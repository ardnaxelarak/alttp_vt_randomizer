<?php

namespace MajorGlitches;

use ALttP\Item;
use ALttP\World;
use TestCase;

/**
 * @group MajorGlitches
 */
class HyruleCastleEscapeTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->world = World::factory(1, 'standard', ['difficulty' => 'test_rules', 'logic' => 'MajorGlitches']);
        $this->collected->setChecksForWorld($this->world->id);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->world);
    }

    /**
     * @param string $location
     * @param bool $access
     * @param array $items
     * @param array $except
     *
     * @dataProvider accessPool
     */
    public function testLocation(string $location, bool $access, array $items, array $except = [])
    {
        if (count($except)) {
            $this->collected = $this->allItemsExcept($except);
        }

        $this->addCollected($items);

        $this->assertEquals($access, $this->world->getLocation($location)
            ->canAccess($this->collected));
    }

    /**
     * @param string $location
     * @param bool $access
     * @param string $item
     * @param array $items
     * @param array $except
     *
     * @dataProvider fillPool
     */
    public function testFillLocation(string $location, bool $access, string $item, array $items = [], array $except = [])
    {
        if (count($except)) {
            $this->collected = $this->allItemsExcept($except);
        }

        $this->addCollected($items);

        $this->assertEquals($access, $this->world->getLocation($location)
            ->fill(Item::get($item, $this->world), $this->collected));
    }

    public function fillPool()
    {
        return [

            ["Sanctuary", false, 'KeyH2', [], ['KeyH2']],

            ["Sewers - Secret Room - Left", false, 'KeyH2', [], ['KeyH2']],

            ["Sewers - Secret Room - Middle", false, 'KeyH2', [], ['KeyH2']],

            ["Sewers - Secret Room - Right", false, 'KeyH2', [], ['KeyH2']],

            ["Sewers - Dark Cross", true, 'KeyH2', [], ['KeyH2']],

            ["Hyrule Castle - Boomerang Chest", true, 'KeyH2', [], ['KeyH2']],

            ["Hyrule Castle - Map Chest", true, 'KeyH2', [], ['KeyH2']],

            ["Hyrule Castle - Zelda's Cell", true, 'KeyH2', [], ['KeyH2']],
        ];
    }

    public function accessPool()
    {
        return [
            ["Sanctuary", true, ['UncleSword', 'KeyH2']],

            ["Sewers - Secret Room - Left", true, ['UncleSword', 'KeyH2']],

            ["Sewers - Secret Room - Middle", true, ['UncleSword', 'KeyH2']],

            ["Sewers - Secret Room - Right", true, ['UncleSword', 'KeyH2']],

            ["Sewers - Dark Cross", true, ['UncleSword']],

            ["Hyrule Castle - Boomerang Chest", true, ['UncleSword']],

            ["Hyrule Castle - Map Chest", true, ['UncleSword']],

            ["Hyrule Castle - Zelda's Cell", true, ['UncleSword']],
        ];
    }
}
