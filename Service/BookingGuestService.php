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

use Tour\Storage\MySQL\TourBookingGuestMapperInterface;

final class BookingGuestService
{
    /**
     * Compliant mapper
     * 
     * @var Tour\Storage\MySQL\TourBookingGuestMapperInterface
     */
    private $tourBookingGuestMapper;

    /**
     * State initialization
     * 
     * @param \Tour\Storage\MySQL\TourBookingGuestMapperInterface $tourBookingGuestMapper
     * @return void
     */
    public function __construct(TourBookingGuestMapperInterface $tourBookingGuestMapper)
    {
        $this->tourBookingGuestMapper = $tourBookingGuestMapper;
    }
}
