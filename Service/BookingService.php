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

use Cms\Service\AbstractManager;
use Tour\Storage\TourBookingMapperInterface;

final class BookingService extends AbstractManager
{
    /**
     * Any compliant tour booking mapper
     * 
     * @var \Tour\Storage\TourBookingMapperInterface
     */
    private $tourBookingMapper;

    /**
     * State initialization
     * 
     * @param \Tour\Storage\TourBookingMapperInterface $tourBookingMapper
     * @return void
     */
    public function __construct(TourBookingMapperInterface $tourBookingMapper)
    {
        $this->tourBookingMapper = $tourBookingMapper;
    }
}
