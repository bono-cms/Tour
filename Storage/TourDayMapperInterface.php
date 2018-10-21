<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Tour\Storage;

interface TourDayMapperInterface
{
    /**
     * Fetch all items
     * 
     * @param int $tourId Attached tour ID
     * @return array
     */
    public function fetchAll($tourId);

    /**
     * Fetches tour day by its associated id
     * 
     * @param string $id
     * @param boolean $withTranslations Whether to fetch translations or not
     * @return array
     */
    public function fetchById($id, $withTranslations);
}
