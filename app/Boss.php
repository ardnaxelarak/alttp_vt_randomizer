<?php

namespace ALttP;

use ALttP\Support\BossCollection;
use ALttP\Support\ItemCollection;
use ALttP\Support\LocationCollection;

/**
 * Boss Logic for beating each boss
 */
class Boss
{
    /** @var string */
    protected $name;
    /** @var string */
    protected $enemizer_name;
    /** @var callable|null */
    protected $can_beat;
    /** @var array */
    protected static $items;
    /** @var array */
    protected static $worlds = [];

    /**
     * Get the Boss by name
     *
     * @param string $name Name of Boss
     * @param \ALttP\World  $world  World boss belongs to
     *
     * @throws \Exception if the Boss doesn't exist
     *
     * @return \ALttP\Boss
     */
    public static function get(string $name, World $world): Boss
    {
        $items = static::all($world);
        if (isset($items[$name])) {
            return $items[$name];
        }

        throw new \Exception('Unknown Boss: ' . $name);
    }

    /**
     * Clears the internal cache so we don't leak memory in testing.
     *
     * @return void
     */
    public static function clearCache(): void
    {
        static::$items = [];
        static::$worlds = [];
    }

    /**
     * Get the all known Bosses
     *
     * @return \ALttP\Support\BossCollection
     */
    public static function all(World $world): BossCollection
    {
        if (isset(static::$items[$world->id])) {
            return static::$items[$world->id];
        }
        static::$worlds[$world->id] = $world;

        static::$items[$world->id] = new BossCollection([
            new static("Armos Knights", "Armos", function ($locations, $items) use ($world) {
                if ($world->restrictedToSpecialWeapons()) {
                    return $items->hasSpecialWeaponLevel($world, 1);
                }
                return $items->hasRealSword($world) || $items->has('Hammer') || $items->canShootArrows($world)
                    || $items->has('Boomerang') || $items->has('RedBoomerang')
                    || ($items->canExtendMagic($world, 4) && ($items->has('FireRod') || $items->has('IceRod')))
                    || ($items->canExtendMagic($world, 2) && ($items->has('CaneOfByrna') || $items->has('CaneOfSomaria')))
                    || $items->hasSpecialWeaponLevel($world, 1);
            }),
            new static("Lanmolas", "Lanmola", function ($locations, $items) use ($world) {
                if ($world->restrictedToSpecialWeapons()) {
                    return $items->hasSpecialWeaponLevel($world, 1);
                }
                return $items->hasRealSword($world) || $items->has('Hammer')
                    || $items->canShootArrows($world) || $items->has('FireRod') || $items->has('IceRod')
                    || $items->has('CaneOfByrna') || $items->has('CaneOfSomaria')
                    || $items->hasSpecialWeaponLevel($world, 1);
            }),
            new static("Moldorm", "Moldorm", function ($locations, $items) use ($world) {
                if ($world->restrictedToSpecialWeapons()) {
                    return $items->hasSpecialWeaponLevel($world, 1);
                }
                return $items->hasRealSword($world) || $items->has('Hammer')
                    || $items->hasSpecialWeaponLevel($world, 1);
            }),
            new static("Agahnim", "Agahnim", function ($locations, $items) {
                return $items->hasSword() || $items->has('Hammer') || $items->has('BugCatchingNet');
            }),
            new static("Helmasaur King", "Helmasaur", function ($locations, $items) use ($world) {
                if ($world->restrictedToSpecialWeapons()) {
                    return $items->hasSpecialWeaponLevel($world, 2)
                        && ($items->canBombThings($world) || $items->has('Hammer'));
                }
                return ($items->canBombThings($world) || $items->has('Hammer'))
                    && ($items->hasRealSword($world, 2) || $items->canShootArrows($world)
                        || ($world->config('itemPlacement') !== 'basic' && $items->hasRealSword($world))
                        || $items->hasSpecialWeaponLevel($world, 2));
            }),
            new static("Arrghus", "Arrghus", function ($locations, $items) use ($world) {
                if ($world->restrictedToSpecialWeapons()) {
                    return $items->has('Hookshot') && $items->hasSpecialWeaponLevel($world, 2);
                }
                return ($world->config('itemPlacement') !== 'basic'
                        || $world->restrictedRealSwords() || $items->hasRealSword($world, 2))
                    && $items->has('Hookshot')
                    && ($items->has('Hammer') || $items->hasRealSword($world)
                        || $items->hasSpecialWeaponLevel($world, 2)
                        || (($items->canExtendMagic($world, 2) || $items->canShootArrows($world))
                            && ($items->has('FireRod') || $items->has('IceRod'))));
            }),
            new static("Mothula", "Mothula", function ($locations, $items) use ($world) {
                if ($world->restrictedToSpecialWeapons()) {
                    return $items->hasSpecialWeaponLevel($world, 1)
                        && ($world->config('itemPlacement') !== 'basic' || $items->hasSpecialWeaponLevel($world, 2));
                }
                return ($world->config('itemPlacement') !== 'basic' || $items->hasRealSword($world, 2)
                        || ($items->canExtendMagic($world, 2) && $items->has('FireRod')))
                    && ($items->hasRealSword($world) || $items->has('Hammer') || $items->canGetGoodBee($world)
                        || $items->hasSpecialWeaponLevel($world, 1)
                        || ($items->canExtendMagic($world, 2) && ($items->has('FireRod') || $items->has('CaneOfSomaria')
                            || $items->has('CaneOfByrna'))));
            }),
            new static("Blind", "Blind", function ($locations, $items) use ($world) {
                if ($world->restrictedToSpecialWeapons()) {
                    return $items->hasSpecialWeaponLevel($world, 1);
                }
                return ($world->config('itemPlacement') !== 'basic' || $world->restrictedRealSwords()
                        || ($items->hasRealSword($world) && ($items->has('Cape') || $items->has('CaneOfByrna'))))
                    && ($items->hasRealSword($world) || $items->has('Hammer')
                        || $items->hasSpecialWeaponLevel($world, 1)
                        || $items->has('CaneOfSomaria') || $items->has('CaneOfByrna'));
            }),
            new static("Kholdstare", "Kholdstare", function ($locations, $items) use ($world) {
                if ($world->restrictedToSpecialWeapons()) {
                    return $items->canMeltThings($world) && $items->hasSpecialWeaponLevel($world, 2)
                        && ($world->config('itemPlacement') !== 'basic' || $items->hasSpecialWeaponLevel($world, 3));
                }
                return ($world->config('itemPlacement') !== 'basic' || $items->hasRealSword($world, 2) || ($items->canExtendMagic($world, 3) && $items->has('FireRod'))
                        || ($items->has('Bombos') && ($world->restrictedMedallions() || $items->canUseMedallions($world))
                            && $items->canExtendMagic($world, 2) && $items->has('FireRod')))
                    && $items->canMeltThings($world)
                    && ($items->has('Hammer') || $items->hasRealSword($world)
                        || $items->hasSpecialWeaponLevel($world, 2)
                        || ($items->canExtendMagic($world, 3) && $items->has('FireRod'))
                        || ($items->canExtendMagic($world, 2) && $items->has('FireRod')
                            && $items->has('Bombos') && ($world->restrictedMedallions() || $items->canUseMedallions($world))));
            }),
            new static("Vitreous", "Vitreous", function ($locations, $items) use ($world) {
                if ($world->restrictedToSpecialWeapons()) {
                    return $items->hasSpecialWeaponLevel($world, 2)
                        && ($world->config('itemPlacement') !== 'basic' || $items->hasSpecialWeaponLevel($world, 3));
                }
                return ($world->config('itemPlacement') !== 'basic' || $items->hasRealSword($world, 2)
                        || $items->hasSpecialWeaponLevel($world, 3)
                        || $items->canShootArrows($world))
                    && ($items->has('Hammer') || $items->hasRealSword($world) || $items->canShootArrows($world)
                        || $items->hasSpecialWeaponLevel($world, 2));
            }),
            new static("Trinexx", "Trinexx", function ($locations, $items) use ($world) {
                if (!($items->has('FireRod') && $items->has('IceRod'))) {
                    return false;
                }
                if ($world->restrictedToSpecialWeapons()) {
                    return $items->hasSpecialWeaponLevel($world, 3)
                        && ($world->config('itemPlacement') !== 'basic' || $items->hasSpecialWeaponLevel($world, 4));
                }
                return ($world->config('itemPlacement') !== 'basic'
                        || $world->restrictedRealSwords() || $items->hasRealSword($world, 3)
                        || ($items->canExtendMagic($world, 2) && $items->hasRealSword($world, 2)))
                    && ($items->hasRealSword($world, 3) || $items->has('Hammer')
                        || $items->hasSpecialWeaponLevel($world, 3)
                        || ($items->canExtendMagic($world, 2) && $items->hasSpecialWeaponLevel($world, 2))
                        || ($items->canExtendMagic($world, 2) && $items->hasRealSword($world, 2))
                        || ($items->canExtendMagic($world, 4) && $items->hasRealSword($world)));
            }),
            new static("Agahnim2", "Agahnim2", function ($locations, $items) {
                return $items->hasSword() || $items->has('Hammer') || $items->has('BugCatchingNet');
            }),
        ]);

        return static::all($world);
    }

    /**
     * Create a new Item.
     *
     * @param string         $name      Unique name of Boss
     * @param callable|null  $can_beat  Rules for beating the Boss
     *
     * @return void
     */
    public function __construct(string $name, string $ename = null, callable $can_beat = null)
    {
        $this->name = $name;
        $this->enemizer_name = $ename ?? $name;
        $this->can_beat = $can_beat;
    }

    /**
     * Get the name of this Boss.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the name of this Boss for Enemizer.
     *
     * @return string
     */
    public function getEName(): string
    {
        return $this->enemizer_name;
    }

    /**
     * Determine if Link can beat this Boss.
     *
     * @param \ALttP\Support\ItemCollection           $items      Items Link can collect
     * @param \ALttP\Support\LocationCollection|null  $locations
     *
     * @return bool
     */
    public function canBeat(ItemCollection $items, ?LocationCollection $locations = null): bool
    {
        if ($this->can_beat === null || call_user_func($this->can_beat, $locations ?? new LocationCollection, $items)) {
            return true;
        }

        return false;
    }
}
