<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Tour;

use Krystal\Image\Tool\ImageManager;
use Cms\AbstractCmsModule;
use Tour\Service\CategoryService;
use Tour\Service\TourService;
use Tour\Service\TourDayService;
use Tour\Service\TourGalleryService;

final class Module extends AbstractCmsModule
{
    /**
     * Builds gallery image manager service
     * 
     * @return \Krystal\Image\Tool\ImageManager
     */
    private function createTourGalleryImageManager()
    {
        $plugins = array(
            'thumb' => array(
                'quality' => 80,
                'dimensions' => array(
                    // For administration panel
                    array(400, 400)
                )
            )
        );

        return new ImageManager(
            '/data/uploads/module/tour/gallery/',
            $this->appConfig->getRootDir(),
            $this->appConfig->getRootUrl(),
            $plugins
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getServiceProviders()
    {
        $categoryMapper = $this->getMapper('/Tour/Storage/MySQL/CategoryMapper');
        $tourMapper = $this->getMapper('/Tour/Storage/MySQL/TourMapper');
        $tourDayMapper = $this->getMapper('/Tour/Storage/MySQL/TourDayMapper');
        $tourGalleryMapper = $this->getMapper('/Tour/Storage/MySQL/TourGalleryMapper');

        $webPageManager = $this->getWebPageManager();

        return array(
            'categoryService' => new CategoryService($categoryMapper, $webPageManager),
            'tourService' => new TourService($tourMapper, $webPageManager),
            'tourDayService' => new TourDayService($tourDayMapper),
            'tourGalleryService' => new TourGalleryService($tourGalleryMapper, $this->createTourGalleryImageManager())
        );
    }
}
