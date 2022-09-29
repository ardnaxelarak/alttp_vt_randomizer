<?php

namespace ALttP\Location;

use ALttP\Location;
use ALttP\Item;
use ALttP\Rom;

/**
 * Master Sword Pedestal Location
 */
class Pedestal extends Location
{
    /**
     * Sets the item for this location. The L2Sword normally sits here, so if we get MasterSword as our Item we need to
     * change it to the L2Sword, it will make the pulling of the sword look better.
     *
     * @param Item|null $item Item to be placed at this Location
     *
     * @return $this
     */
    public function setItem(Item $item = null)
    {
        if ($item == Item::get('MasterSword', $this->region->getWorld())) {
            $item = Item::get('L2Sword', $this->region->getWorld());
        }

        return parent::setItem($item);
    }

    /**
     * Write item to ROM.
     *
     * @param \ALttP\Rom  $rom  ROM to write to
     * @param \ALttP\Item $item item to write
     *
     * @return \ALttP\Location
     */
    public function writeItem(Rom $rom, Item $item = null)
    {
        parent::writeItem($rom, $item);

        $rom->setCredit('pedestal', $this->getItemCreditsText());
        $rom->setText('mastersword_pedestal_translated', $this->getItemPedestalText());

        return $this;
    }


    private function getItemCreditsText()
    {
        return "and the swirly coin";
    }

    private function getItemPedestalText()
    {
        return "Burn, baby,\nburn! Fear my\nring of fire!";
    }
}
