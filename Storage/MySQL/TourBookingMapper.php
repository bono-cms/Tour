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
     * Creates shared select
     * 
     * @return \Krystal\Db\Sql\Db
     */
    private function createSharedSelect()
    {
        // Columns to be selected
        $columns = [
            self::column('id'),
            self::column('tour_id'),
            self::column('date_id'),
            TourDateMapper::column('start'),
            TourDateMapper::column('end'),
            self::column('status'),
            self::column('tour'),
            self::column('client'),
            self::column('email'),
            self::column('phone'),
            self::column('datetime'),
            self::column('amount'),
            self::column('token')
        ];

        $db = $this->db->select($columns)
                       ->from(self::getTableName())
                       // Tour date relation
                       ->leftJoin(TourDateMapper::getTableName(), [
                        TourDateMapper::column('tour_id') => self::getRawColumn('tour_id')
                       ]);

        return $db;
    }

    /**
     * Updates booking status by its token
     * 
     * @param string $token
     * @param int $status
     * @return boolean
     */
    public function updateStatusByToken($token, $status)
    {
        return $this->db->update(self::getTableName(), array('status' => $status))
                        ->whereEquals('token', $token)
                        ->execute();
    }

    /**
     * Finds row by its associated token
     * 
     * @param string $token
     * @return array
     */
    public function findByToken($token)
    {
        $db = $this->createSharedSelect()
                   ->whereEquals(self::column('token'), $token);

        return $db->query();
    }

    /**
     * Fetch all bookings
     * 
     * @param int $page Current page number
     * @param int $itemsPerPage Per page count
     * @return array
     */
    public function fetchAll($page, $itemsPerPage)
    {
        $db = $this->createSharedSelect()
                   ->orderBy(self::column('id'))
                   ->desc();

        // Apply pagination if required
        if ($page !== null && $itemsPerPage !== null){
            $db->paginate($page, $itemsPerPage);
        }

        return $db->queryAll();
    }
}
