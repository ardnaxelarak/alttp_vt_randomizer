<?php

namespace ALttP\Location\Drop;

use ALttP\Location;
use ALttP\Item;
use ALttP\Rom;

/**
 * Bombos Tablet Location
 */
class Bombos extends Location
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

        $rom->setText('tablet_bombos_book', $this->getItemText());

        return $this;
    }

    private function getItemText()
    {
        return "Burn, baby,\nburn! Fear my\nring of fire!";
    }
}
