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
use Tour\Storage\TourDateMapperInterface;

final class TourDateMapper extends AbstractMapper implements TourDateMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('bono_module_tour_dates');
    }

    /**
     * Fetch dates by tour ID
     * 
     * @param int $tourId
     * @return array
     */
    public function fetchByTourId($tourId)
    {
        return $this->fetchAllByColumn('tour_id', $tourId);
    }
}
