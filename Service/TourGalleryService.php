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

use Tour\Storage\TourGalleryMapperInterface;

final class TourGalleryService
{
    /**
     * Any tour gallery mapper
     * 
     * @var \Tour\Storage\TourGalleryMapperInterface
     */
    private $tourGalleryMapper;

    /**
     * State initialization
     * 
     * @param \Tour\Storage\TourGalleryMapperInterface $tourGalleryMapper
     * @return void
     */
    public function __construct(TourGalleryMapperInterface $tourGalleryMapper)
    {
        $this->tourGalleryMapper = $tourGalleryMapper;
    }
}
