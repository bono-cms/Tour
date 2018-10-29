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

interface TourBookingMapperInterface
{
    /**
     * Finds row by its associated token
     * 
     * @param string $token
     * @return array
     */
    public function findByToken($token);

    /**
     * Fetch all bookings
     * 
     * @return array
     */
    public function fetchAll();
}
