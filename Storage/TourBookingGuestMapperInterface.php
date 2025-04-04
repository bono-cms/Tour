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

interface TourBookingGuestMapperInterface
{
    /**
     * Fetch all booking records
     * 
     * @param int $bookingId Booking id
     * @return array
     */
    public function fetchAll($bookingId);

    /**
     * Stores booking guest data
     * 
     * @param int $bookingId Booking id
     * @param array $guests
     * @return boolean
     */
    public function store($bookingId, array $guests);
}
