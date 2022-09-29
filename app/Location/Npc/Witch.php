<?php

namespace ALttP\Location\Npc;

use ALttP\Item;
use ALttP\Location\Npc;
use ALttP\Rom;

/**
 * Witch specifically changes the credits when you write an item.
 */
class Witch extends Npc
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

        $rom->setCredit('witch', $this->getItemCreditsText());

        return $this;
    }

    private function getItemCreditsText()
    {
        return "bombos for swirly-coin";
    }
}
