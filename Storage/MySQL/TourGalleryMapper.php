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
use Tour\Storage\SharedGalleryMapperInterface;

final class TourGalleryMapper extends AbstractGalleryMapper implements SharedGalleryMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('bono_module_tour_gallery');
    }

    /**
     * Fetch all tour images
     * 
     * @param int $tourId Attached tour ID
     * @param boolean $sort Whether to sort by corresponding sorting order
     * @return array
     */
    public function fetchAll($tourId, $sort)
    {
        return $this->findAllImages('tour_id', $tourId, $sort);
    }
}
