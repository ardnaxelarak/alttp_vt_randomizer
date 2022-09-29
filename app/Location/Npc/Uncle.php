<?php

namespace ALttP\Location\Npc;

use ALttP\Location;
use ALttP\Item;
use ALttP\Rom;

/**
 * Uncle Location
 */
class Uncle extends Location
{
    /**
     * Sets the item for this location. We need to check mode to determine fills/assit.
     *
     * @param \ALttP\Rom  $rom  ROM to write to
     * @param \ALttP\Item $item item to write
     *
     * @return \ALttP\Location
     */
    public function writeItem(Rom $rom, Item $item = null)
    {
        parent::writeItem($rom, $item);
        $rom->setCredit('house', $this->getItemCreditsText());
        return $this;
    }

    private function getItemCreditsText()
    {
        return "your uncle collects coins";
    }
}
