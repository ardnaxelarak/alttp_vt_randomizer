<?php

namespace ALttP\Support;

use ALttP\Item;
use ALttP\World;
use ArrayIterator;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Collection of Items, maintains counts of items collected as well.
 */
class ItemCollection extends Collection
{
    protected $item_counts = [];
    private $string_rep = null;
    private $checks_for_world = 0;
    private $cached_values = [];

    /**
     * Create a new collection.
     *
     * @param mixed $items
     *
     * @return void
     */
    public function __construct($items = [])
    {
        parent::__construct($items);

        $this->items = [];

        foreach ($this->getArrayableItems($items) as $item) {
            $this->addItem($item);
        }
    }

    public function setChecksForWorld(int $world_id)
    {
        $this->checks_for_world = $world_id;
    }

    /**
     * Add an Item to this Collection
     *
     * @param Item $item
     *
     * @return $this
     */
    public function addItem(Item $item)
    {
        $item_name = $item->getName();
        $this->offsetSet($item_name, $item);
        if (!isset($this->item_counts[$item_name])) {
            $this->item_counts[$item_name] = 0;
        }

        $this->cached_values[] = $item;
        $this->item_counts[$item_name]++;
        $this->string_rep = null;

        return $this;
    }

    /**
     * Remove an item from the collection by name.
     *
     * @return $this
     */
    public function removeItem($name)
    {
        if (!isset($this->item_counts[$name])) {
            return $this;
        }

        $this->item_counts[$name]--;
        if ($this->item_counts[$name] === 0) {
            $this->offsetUnset($name);
        }
        foreach ($this->cached_values as $key => $item) {
            if ($item->getName() === $name) {
                unset($this->cached_values[$key]);
                break;
            }
        }

        return $this;
    }

    /**
     * Run a filter over each of the items.
     *
     * @param callable|null $callback
     *
     * @return static
     */
    public function filter(callable $callback = null)
    {
        if ($callback) {
            return new static(array_filter($this->values(), $callback));
        }

        return new static(array_filter($this->values()));
    }

    /**
     * Get an array of the underlying elements
     *
     * @return array
     */
    public function values()
    {
        return $this->cached_values;
    }

    /**
     * Get the items in the collection that are not present in the given items.
     *
     * @param mixed $items items to diff against
     *
     * @return static
     */
    public function diff($items)
    {
        if (!count($items)) {
            return $this->copy();
        }

        // TODO: this might not be correct
        if (!is_a($items, static::class)) {
            return parent::diff($items);
        }

        $diffed = $this->copy();

        foreach ($diffed->item_counts as $name => $amount) {
            if (isset($items->item_counts[$name])) {
                if ($items->item_counts[$name] < $amount) {
                    $diffed->item_counts[$name] = $amount - $items->item_counts[$name];
                } else {
                    $diffed->offsetUnset($name);
                }
            }
        }
        return $diffed;
    }

    /**
     * Intersect the collection with the given items.
     *
     * @param  mixed  $items
     *
     * @return static
     */
    public function intersect($items)
    {
        return new static(array_intersect($this->items, $this->getArrayableItems($items)));
    }

    /**
     * Execute a callback over each item.
     *
     * @param callable $callback
     *
     * @return $this
     */
    public function each(callable $callback)
    {
        foreach ($this->items as $key => $item) {
            for ($i = 0; $i < $this->item_counts[$key]; $i++) {
                if ($callback($item, $key) === false) {
                    break;
                }
            }
        }

        return $this;
    }

    /**
     * Merge the collection with the given items.
     *
     * @param mixed $items
     *
     * @return static
     */
    public function merge($items)
    {
        if (!count($items)) {
            return $this->copy();
        }

        if (!$items instanceof static) {
            return $this->merge(new static($items));
        }

        $merged = $this->copy();

        $items->each(function ($item) use ($merged) {
            $merged->addItem($item);
        });

        return $merged;
    }

    /**
     * Get a fresh copy of this object, the underlying items will still be the same
     *
     * @return static
     */
    public function copy()
    {
        $new = new static;
        $new->items = $this->items;
        $new->item_counts = $this->item_counts;
        $new->checks_for_world = $this->checks_for_world;
        $new->cached_values = $this->cached_values;

        return $new;
    }

    /**
     * Reduce the collection to a single value.
     *
     * @param callable $callback
     * @param mixed $initial
     *
     * @return mixed
     */
    public function reduce(callable $callback, $initial = null)
    {
        return array_reduce($this->values(), $callback, $initial);
    }

    /**
     * Run a map over each of the items.
     *
     * @param callable $callback
     *
     * @return array
     */
    public function map(callable $callback)
    {
        return array_map($callback, $this->values());
    }

    /**
     * Determine if an item exists in the collection by key.
     *
     * @param mixed $key
     * @param int $at_least mininum number of item in collection
     *
     * @return bool
     */
    public function has($key, $at_least = 1)
    {
        $key = "$key:$this->checks_for_world";

        if ($at_least === 0) {
            return true;
        }

        if ($at_least == null) {
            return false;
        }

        // @TODO: this check is expensive, as this function is called A LOT, can we reduce it somehow?
        // assuming if there are ShopKey's available then we are in generic key mode, this is a really bad assumption
        // but we need to make it until we can rewrite this class
        if (($this->item_counts["ShopKey:$this->checks_for_world"] ?? false) && strpos($key, 'Key') === 0) {
            return true;
        }

        return ($this->item_counts[$key] ?? 0) >= $at_least;
    }

    /**
     * For testing, we up the key count to 10 for every dungeon.
     *
     * @return $this
     */
    public function manyKeys(): self
    {
        foreach ($this->item_counts as $key => $count) {
            if (strpos($key, 'Key') === 0) {
                $this->item_counts[$key] = 10;
            }
        }

        return $this;
    }

    /**
     * Get the collection of items as a plain array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_map(function ($value) {
            return $value instanceof Arrayable ? $value->toArray() : $value;
        }, $this->values());
    }

    /**
     * Count the number of items in the collection.
     *
     * @return int
     */
    public function count()
    {
        return (int) array_sum($this->item_counts);
    }

    /**
     * Get an iterator for the items.
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->toArray());
    }

    /**
     * Unset the item at a given offset.
     *
     * @param mixed $offset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->item_counts[$offset]);
        unset($this->items[$offset]);
    }

    /**
     * Get total collectable Health
     *
     * @param float $initial starting health
     *
     * @return float
     */
    public function heartCount(float $initial = 3.0)
    {
        $count = $initial;

        $hearts = $this->filter(function ($item) {
            return $item instanceof Item\Upgrade\Health;
        });

        foreach ($hearts as $heart) {
            $count += ($heart->getName() == 'PieceOfHeart') ? .25 : 1;
        }

        return $count;
    }

    /**
     * Requirements for lifting rocks
     *
     * @return bool
     */
    public function canLiftRocks()
    {
        return $this->has('PowerGlove')
            || $this->has('ProgressiveGlove')
            || $this->has('TitansMitt');
    }

    /**
     * Requirements for lifting dark rocks
     *
     * @return bool
     */
    public function canLiftDarkRocks()
    {
        return $this->has('TitansMitt')
            || $this->has('ProgressiveGlove', 2);
    }

    /**
     * Requirements for lighting torches
     *
     * @return bool
     */
    public function canLightTorches()
    {
        return $this->has('FireRod') || $this->has('Lamp');
    }

    /**
     * Requirements for melting things, like ice statues
     * should only be used in places where we have put Bombos pads in swordless
     *
     * @param \ALttP\World  $world  world to check items against
     *
     * @return bool
     */
    public function canMeltThings(World $world)
    {
        return $this->has('FireRod')
            || ($this->has('Bombos') && ($world->restrictedSwords() || $this->hasSword()));
    }

    /**
     * Requirements for fast travel through the duck
     *
     * @param \ALttP\World  $world  world to check items against
     *
     * @return bool
     */
    public function canFly(World $world)
    {
        return $this->has('OcarinaActive') || $this->has('OcarinaInactive') && $this->canActivateOcarina($world);
    }

    private function canActivateOcarina(World $world)
    {
        if ($world instanceof World\Inverted) {
            return $this->has('MoonPearl')
                && ($this->has('DefeatAgahnim')
                    || ((($this->has('Hammer') && $this->canLiftRocks()) || $this->canLiftDarkRocks())));
        }
        return true;
    }

    /**
     * Requirements for fast travel through the spin/hook speed
     *
     * @return bool
     */
    public function canSpinSpeed()
    {
        return $this->has('PegasusBoots')
            && ($this->hasSword() || $this->has('Hookshot'));
    }

    /**
     * Requirements for acquiring a fairy
     */
    public function canAcquireFairy($world = null)
    {
        if ($world !== null && $world->config('rom.HardMode') >= 1)
        {
            return False;
        }
        return True;
    }

    /**
     * Requirements for water (bunny) revival
     *
     * @return bool
     */
    public function canBunnyRevive($world = null): bool
    {
        $canBunnyRevive = $this->hasABottle() && $this->has('BugCatchingNet');

        if ($world !== null)
        {
            $canBunnyRevive = $canBunnyRevive && $this->canAcquireFairy($world);
        }

        return $canBunnyRevive;
    }   
    
    /**
     * Requirements for lobbing arrows at things
     *
     * @param \ALttP\World  $world  world to check items against
     * @param int $min_level minimum level of bow
     *
     * @return bool
     */
    public function canShootArrows(World $world, int $min_level = 1)
    {
        switch ($min_level) {
            case 2:
                return $this->has('BowAndSilverArrows')
                    || ($this->has('ProgressiveBow', 2) 
                        && (!$world->config('rom.rupeeBow', false) || $this->has('ShopArrow')))
                    || ($this->has('SilverArrowUpgrade')
                        && ($this->has('Bow') || $this->has('BowAndArrows') || $this->has('ProgressiveBow')));
            case 1:
            default:
                return (($this->has('Bow') || $this->has('ProgressiveBow')) 
                        && (!$world->config('rom.rupeeBow', false)
                            || $this->has('ShopArrow') || $this->has('SilverArrowUpgrade')))
                    || $this->has('BowAndArrows')
                    || $this->has('BowAndSilverArrows');
        }
    }

    /**
     * Requirements for damaging stunned Ganon
     *
     * @param \ALttP\World  $world  world to check items against
     *
     * @return bool
     */
    public function canHitStunnedGanon(World $world)
    {
        $ganon_item = $world->config('ganon_item', 'default');
        if ($ganon_item === 'default') {
          if ($world->config('mode.weapons') === 'bombs') {
            $ganon_item = 'bomb';
          } else {
            $ganon_item = 'arrow';
          }
        }
        
        switch ($ganon_item) {
            case 'boomerang':
                return $this->has('Boomerang') || $this->has('RedBoomerang');
            case 'hookshot':
                return $this->has('Hookshot');
            case 'bomb':
                return true;
            case 'powder':
                return $this->has('Powder');
            case 'fire_rod':
                return $this->has('FireRod');
            case 'ice_rod':
                return $this->has('IceRod');
            case 'bombos':
                return $this->has('Bombos') && $this->hasSword();
            case 'ether':
                return $this->has('Ether') && $this->hasSword();
            case 'quake':
                return $this->has('Quake') && $this->hasSword();
            case 'hammer':
                return $this->has('Hammer');
            case 'bee':
                return $this->hasABottle() && $this->canGetGoodBee();
            case 'somaria':
                return $this->has('CaneOfSomaria');
            case 'byrna':
                return $this->has('CaneOfByrna');
            case 'arrow':
            default:
                return $this->canShootArrows($world, 2);
        }
    }

    /**
     * Requirements for blocking lasers
     *
     * @return bool
     */
    public function canBlockLasers()
    {
        return $this->has('MirrorShield')
            || $this->has('ProgressiveShield', 3);
    }

    /**
     * Requirements for magic usage
     *
     * @param float $bars number of magic bars
     *
     * @return bool
     */
    public function canExtendMagic($world = null, $bars = 2.0)
    {
        $magicModifier = 1.0;
        if ($world !== null)
        {
            $difficultyLevel = $world->config('rom.HardMode');
            switch ($difficultyLevel)
            {
                case 1:
                    $magicModifier = 0.5;
                    break;
                case 2:
                    $magicModifier = 0.25;
                    break;
                case 3:
                    $magicModifier = 0.0;
                    break;
                default:
                    break;
            }
        }

        $baseMagic = ($this->has('QuarterMagic') ? 4 : ($this->has('HalfMagic') ? 2 : 1));
        $bottleMagic = $baseMagic * $this->bottleCount() * $magicModifier;
        return ($baseMagic + $bottleMagic) >= $bars;
    }

    /**
     * Requirements for being link in Dark World using Major Glitches
     *
     * @return bool
     */
    public function glitchedLinkInDarkWorld()
    {
        return $this->has('MoonPearl')
            || $this->hasABottle();
    }

    /**
     * Check if player has access to enough health.
     *
     * @param float $minimum minimum health
     *
     * @return bool
     */
    public function hasHealth(float $minimum): bool
    {
        return $this->filter(function ($item) {
            return $item instanceof Item\Upgrade\Health;
        })->reduce(function ($carry, $item) {
            return $carry + $item->power;
        }, 0) >= $minimum;
    }

    /**
     * Requirements for killing most things
     *
     * @param \ALttP\World  $world  world to check items against
     *
     * @return bool
     */
    public function canKillEscapeThings(World $world)
    {
        return $this->has('UncleSword')
            || $this->has('CaneOfSomaria')
            || ($this->has('TenBombs')
                && $world->config('enemizer.enemyHealth', 'default') == 'default')
            || ($this->has('CaneOfByrna')
                && $world->config('enemizer.enemyHealth', 'default') == 'default')
            || $this->canShootArrows($world)
            || $this->has('Hammer')
            || $this->has('FireRod')
            || $world->config('ignoreCanKillEscapeThings', false)
            || $world->config('mode.weapons') === 'bombs';
    }

    /**
     * Requirements for killing most things
     *
     * @param \ALttP\World  $world  world to check items against
     * @param mixed $enemies Amount of Damage Enemies need to be beaten
     *
     * @return bool
     */
    public function canKillMostThings(World $world, $enemies = 5)
    {
        return $this->hasSword()
            || $this->has('CaneOfSomaria')
            || ($this->canBombThings() && $enemies < 6
                && $world->config('enemizer.enemyHealth', 'default') == 'default')
            || ($this->has('CaneOfByrna') && ($enemies < 6 || $this->canExtendMagic())
                && $world->config('enemizer.enemyHealth', 'default') == 'default')
            || $this->canShootArrows($world)
            || $this->has('Hammer')
            || $this->has('FireRod')
            || $world->config('mode.weapons') === 'bombs';
    }

    /**
     * Requirements for bombing things
     *
     * @return bool
     */
    public function canBombThings()
    {
        return true;
    }

    /**
     * Requirements for catching a Golden Bee
     *
     * @return bool
     */
    public function canGetGoodBee()
    {
        return $this->has('BugCatchingNet')
            && $this->hasABottle()
            && ($this->has('PegasusBoots')
                || ($this->hasSword() && $this->has('Quake')));
    }

    /**
     * Requirements for having a sword, we treat the special UncleSword like a progressive sword.
     *
     * @param int $min_level minimum level of sword
     *
     * @return bool
     */
    public function hasSword(int $min_level = 1)
    {
        switch ($min_level) {
            case 4:
                return $this->has('ProgressiveSword', 4)
                    || $this->has('UncleSword') && $this->has('ProgressiveSword', 3)
                    || $this->has('L4Sword');
            case 3:
                return $this->has('ProgressiveSword', 3)
                    || $this->has('UncleSword') && $this->has('ProgressiveSword', 2)
                    || $this->has('L3Sword')
                    || $this->has('L4Sword');
            case 2:
                return $this->has('ProgressiveSword', 2)
                    || $this->has('UncleSword') && $this->has('ProgressiveSword')
                    || $this->has('L2Sword')
                    || $this->has('MasterSword')
                    || $this->has('L3Sword')
                    || $this->has('L4Sword');
            case 1:
            default:
                return $this->has('ProgressiveSword')
                    || $this->has('UncleSword')
                    || $this->has('L1Sword')
                    || $this->has('L1SwordAndShield')
                    || $this->has('L2Sword')
                    || $this->has('MasterSword')
                    || $this->has('L3Sword')
                    || $this->has('L4Sword');
        }
    }

    /**
     * Requirements for having a certain level of bombs, for bomb-only mode
     *
     * @param int $min_level minimum level of bombs
     *
     * @return bool
     */
    public function hasBombLevel(int $min_level)
    {
        switch ($min_level) {
            case 4:
                return $this->has('ProgressiveBombs', 3)
                    || $this->has('L4Bombs')
                    || $this->has('L5Bombs');
            case 3:
                return $this->has('ProgressiveBombs', 2)
                    || $this->has('L3Bombs')
                    || $this->has('L4Bombs')
                    || $this->has('L5Bombs');
            case 2:
                return $this->has('ProgressiveBombs')
                    || $this->has('L2Bombs')
                    || $this->has('L3Bombs')
                    || $this->has('L4Bombs')
                    || $this->has('L5Bombs');
            case 1:
            default:
                return true;
        }
    }

    /**
     * Requirements for having armor.
     *
     * @param int $min_level minimum level of armor
     *
     * @return bool
     */
    public function hasArmor(int $min_level = 1): bool
    {
        switch ($min_level) {
            case 2:
                return $this->has('ProgressiveArmor', 2)
                    || $this->has('RedMail');
            case 1:
            default:
                return $this->has('ProgressiveArmor')
                    || $this->has('BlueMail')
                    || $this->has('RedMail');
        }
    }

    /**
     * Requirements for having X bottles.
     *
     * @param int $at_least mininum number of item in collection
     *
     * @return bool
     */
    public function hasBottle(int $at_least = 1): bool
    {
        return $this->bottleCount() >= $at_least;
    }

    /**
     * Count the bottles that are in the collection.
     *
     * @return int
     */
    public function bottleCount(): int
    {
        return $this->filter(function ($item) {
            return $item instanceof Item\Bottle;
        })->count();
    }

    /**
     * Requirements for having a bottle.
     *
     * @return bool
     */
    public function hasABottle()
    {
        return $this->has('BottleWithBee')
            || $this->has('BottleWithFairy')
            || $this->has('BottleWithRedPotion')
            || $this->has('BottleWithGreenPotion')
            || $this->has('BottleWithBluePotion')
            || $this->has('Bottle')
            || $this->has('BottleWithGoldBee');
    }

    public function __toString()
    {
        if ($this->string_rep === null) {
            $this->string_rep = $this->reduce(function ($carry, $item) {
                return $carry . $item->getName();
            }, '');
        }

        return $this->string_rep;
    }
}
