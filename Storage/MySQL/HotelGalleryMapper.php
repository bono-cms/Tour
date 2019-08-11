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
use Tour\Storage\HotelGalleryMapperInterface;

final class HotelGalleryMapper extends AbstractMapper implements HotelGalleryMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('bono_module_tour_hotels_gallery');
    }

    /**
     * Fetch all images
     * 
     * @param int $hotelId
     * @return array
     */
    public function fetchAll($hotelId)
    {
        $db = $this->db->select('*')
                       ->from(self::getTableName())
                       ->whereEquals('hotel_id', $hotelId)
                       ->orderBy('id')
                       ->desc();

        return $db->queryAll();
    }
}
