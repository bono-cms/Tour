<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Tour\Storage;

interface TourPricePolicyMapperInterface
{
    /**
     * Attempts to find a price
     * 
     * @param int $tourId Attached tour id
     * @param int $qty Number of people
     * @return string
     */
    public function findPrice($tourId, $qty);

    /**
     * Fetch all policies
     * 
     * @param int $tourId
     * @return array
     */
    public function fetchAll($tourId);
}
