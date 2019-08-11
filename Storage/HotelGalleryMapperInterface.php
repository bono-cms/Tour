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

interface HotelGalleryMapperInterface
{
    /**
     * Fetch all images
     * 
     * @param int $tourId
     * @return array
     */
    public function fetchAll($tourId);
}