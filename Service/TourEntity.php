<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Tour\Service;

use Krystal\Stdlib\VirtualEntity;

final class TourEntity extends VirtualEntity
{
    /**
     * Checks whether current tour has a price
     * 
     * @return boolean
     */
    public function hasPrice()
    {
        $price = $this->getPrice();
        return $price && $price > 0;
    }

    /**
     * Checks whether current tour has a starting price
     * 
     * @return boolean
     */
    public function hasStartPrice()
    {
        $price = $this->getStartPrice();
        return $price && $price > 0;
    }
}
