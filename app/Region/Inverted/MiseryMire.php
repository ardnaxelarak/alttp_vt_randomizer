<?php

namespace ALttP\Region\Inverted;

use ALttP\Item;
use ALttP\Region;

/**
 * Misery Mire Region and it's Locations contained within
 */
class MiseryMire extends Region\Standard\MiseryMire
{
    /**
     * Initalize the requirements for Entry and Completetion of the Region as well as access to all Locations contained
     * within for No Glitches
     *
     * @return $this
     */
    public function initalize()
    {
        parent::initalize();

        $this->can_enter = function ($locations, $items) {
            return ($this->world->config('itemPlacement') !== 'basic'
                || (
                    ($this->world->restrictedRealSwords() || $items->hasRealSword($this->world, 2))
                    && $items->hasHealth(12) && ($items->hasBottle(2) || $items->hasArmor())))
                && (
                    ($locations["Misery Mire Medallion"]->hasItem(Item::get('Bombos', $this->world)) && $items->has('Bombos'))
                    || ($locations["Misery Mire Medallion"]->hasItem(Item::get('Ether', $this->world)) && $items->has('Ether'))
                    || ($locations["Misery Mire Medallion"]->hasItem(Item::get('Quake', $this->world)) && $items->has('Quake')))
                && ($this->world->restrictedMedallions() || $items->canUseMedallions($this->world))
                && ($items->has('PegasusBoots') || $items->has('Hookshot'))
                && $items->canKillMostThings($this->world, 8)
                && $this->world->getRegion('Mire')->canEnter($locations, $items);
        };

        return $this;
    }
}
