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
use Tour\Storage\TourPricePolicyMapperInterface;
use Krystal\Db\Sql\RawBinding;

final class TourPricePolicyMapper extends AbstractMapper implements TourPricePolicyMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('bono_module_tour_price_policy');
    }

    /**
     * Checks tour id and qty combination for existence
     * 
     * @param int $tourId Attached tour id
     * @param int $qty Number of people
     * @return boolean
     */
    public function hasQty($tourId, $qty)
    {
        $db = $this->db->select()
                       ->count('id')
                       ->from(self::getTableName())
                       ->whereEquals('tour_id', $tourId)
                       ->andWhereEquals('qty', $qty);

        return (bool) $db->queryScalar();
    }

    /**
     * Attempts to find a price
     * 
     * @param int $tourId Attached tour id
     * @param int $qty Number of people
     * @return string
     */
    public function findPrice($tourId, $qty)
    {
        $db = $this->db->select([
                            TourMapper::column('price'),
                            TourMapper::column('start_price'),
                            self::column('price') => 'qty_price'
                       ])
                       ->from(TourMapper::getTableName())
                       // Tour price policy
                       ->leftJoin(self::getTableName(), [
                            self::column('tour_id') => TourMapper::getRawColumn('id'),
                            self::column('qty') => new RawBinding((int) $qty)
                       ])
                       ->whereEquals(TourMapper::column('id'), $tourId);

        return $db->query();
    }

    /**
     * Fetch all policies
     * 
     * @param int $tourId
     * @return array
     */
    public function fetchAll($tourId)
    {
        $db = $this->db->select('*')
                       ->from(self::getTableName())
                       ->whereEquals('tour_id', $tourId)
                       ->orderBy('qty');

        return $db->queryAll();
    }
}
