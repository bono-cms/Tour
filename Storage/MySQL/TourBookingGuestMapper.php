<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Tour\Storage\MySQL;

use Cms\Storage\MySQL\AbstractMapper;
use Tour\Storage\TourBookingGuestMapperInterface;

final class TourBookingGuestMapper extends AbstractMapper implements TourBookingGuestMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('bono_module_tour_booking_guests');
    }

    /**
     * Stores booking guest data
     * 
     * @param int $bookingId Booking id
     * @param array $guests
     * @return boolean
     */
    public function store($bookingId, array $guests)
    {
        $columns = [
            'booking_id',
            'title',
            'first_name',
            'last_name',
            'birth',
            'address_primary',
            'address_secondary',
            'email',
            'city',
            'state',
            'country',
            'phone',
            'postal'
        ];

        // Set booking guest id
        foreach ($guests as &$guest) {
            array_unshift($guest, $bookingId);
        }

        return (bool) $this->db->insertMany(self::getTableName(), $columns, $guests)
                               ->execute(true);
    }
}
