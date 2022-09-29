<?php

namespace ALttP\Location\Dig;

use ALttP\Item;
use ALttP\Location\Dig;
use ALttP\Rom;

/**
 * Haunted Grove specifically changes the credits when you write an item.
 */
class HauntedGrove extends Dig
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

        $rom->setCredit('grove', $this->getItemCreditsText());

        return $this;
    }

    private function getItemCreditsText()
    {
        return "medallion boy melts room again";
    }
}
