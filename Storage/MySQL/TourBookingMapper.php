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
use Tour\Storage\TourBookingMapperInterface;

final class TourBookingMapper extends AbstractMapper implements TourBookingMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('bono_module_tour_booking');
    }

    /**
     * Finds row by its associated token
     * 
     * @param string $token
     * @return array
     */
    public function findByToken(string $token)
    {
        return $this->fetchByColumn('token', $token);
    }

    /**
     * Fetch all bookings
     * 
     * @return array
     */
    public function fetchAll()
    {
        $db = $this->db->select('*')
                       ->from(self::getTableName())
                       ->orderBy('id')
                       ->desc();

        return $db->queryAll();
    }
}
