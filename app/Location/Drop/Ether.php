<?php

namespace ALttP\Location\Drop;

use ALttP\Location;
use ALttP\Item;
use ALttP\Rom;

/**
 * Ether Tablet Location
 */
class Ether extends Location
{
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

        $rom->setText('tablet_ether_book', $this->getItemText());

        return $this;
    }

    private function getItemText()
    {
        return "Burn, baby,\nburn! Fear my\nring of fire!";
    }
}
