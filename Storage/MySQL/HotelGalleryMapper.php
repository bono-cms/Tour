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

use Tour\Storage\SharedGalleryMapperInterface;

final class HotelGalleryMapper extends AbstractGalleryMapper implements SharedGalleryMapperInterface
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
     * @param boolean $sort Whether to sort by corresponding sorting order
     * @return array
     */
    public function fetchAll($hotelId, $sort)
    {
        return $this->findAllImages('hotel_id', $hotelId, $sort);
    }
}
