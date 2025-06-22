<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Tour\Service;

use Krystal\Stdlib\VirtualEntity;

final class HotelEntity extends VirtualEntity
{
    /**
     * Checks whether there's available gallery
     * 
     * @return boolean
     */
    public function hasGallery()
    {
        return (bool) $this->getGallery();
    }

    /**
     * Returns a path to image URL
     * 
     * @param string $size
     * @return string
     */
    public function getImageUrl($size)
    {
        return $this->getImageBag()->getUrl($size);
    }
}