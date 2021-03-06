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
     * Updates booking status by its token
     * 
     * @param string $token
     * @param int $status
     * @return boolean
     */
    public function updateStatusByToken($token, $status);

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
     * @param int $page Current page number
     * @param int $itemsPerPage Per page count
     * @return array
     */
    public function fetchAll($page, $itemsPerPage);
}
