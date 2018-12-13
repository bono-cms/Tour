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

interface TourDestinationMapperInterface
{
    /**
     * Fetch tour destination by its ID
     * 
     * @param int $id Tour destination ID
     * @param boolean $withTranslations Whether to fetch translations or not
     * @return mixed
     */
    public function fetchById($id, $withTranslations);
}
