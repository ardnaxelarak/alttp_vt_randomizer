<?php

namespace ALttP\Location\Npc;

use ALttP\Item;
use ALttP\Location\Npc;
use ALttP\Rom;

/**
 * Zora specifically changes the credits when you write an item.
 */
class Zora extends Npc
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

        $rom->setCredit('zora', $this->getItemCreditsText());

        return $this;
    }

    private function getItemCreditsText()
    {
        return "swirly coin for sale";
    }
}
