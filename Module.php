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
use Tour\Service\BookingService;
use Tour\Service\TourReviewService;
use Tour\Service\TourDateService;
use Tour\Service\TourDestinationService;
use Tour\Service\SiteService;

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
            ),

            'original' => array(
                'prefix' => 'original'
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
     * Builds gallery image manager service
     * 
     * @return \Krystal\Image\Tool\ImageManager
     */
    private function createTourCoverImageManager()
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
            '/data/uploads/module/tour/cover/',
            $this->appConfig->getRootDir(),
            $this->appConfig->getRootUrl(),
            $plugins
        );
    }

    /**
     * Builds category image manager service
     * 
     * @return \Krystal\Image\Tool\ImageManager
     */
    private function createCategoryImageManager()
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
            '/data/uploads/module/tour/category/',
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
        // Create mappers
        $categoryMapper = $this->getMapper('/Tour/Storage/MySQL/CategoryMapper');
        $tourMapper = $this->getMapper('/Tour/Storage/MySQL/TourMapper');
        $tourDayMapper = $this->getMapper('/Tour/Storage/MySQL/TourDayMapper');
        $tourGalleryMapper = $this->getMapper('/Tour/Storage/MySQL/TourGalleryMapper');
        $tourDateMapper = $this->getMapper('/Tour/Storage/MySQL/TourDateMapper');
        $tourDestinationMapper = $this->getMapper('/Tour/Storage/MySQL/TourDestinationMapper');
        $bookingMapper = $this->getMapper('/Tour/Storage/MySQL/TourBookingMapper');
        $reviewMapper = $this->getMapper('/Tour/Storage/MySQL/TourReviewMapper');

        $webPageManager = $this->getWebPageManager();

        // Category service
        $categoryService = new CategoryService($categoryMapper, $webPageManager, $this->createCategoryImageManager());
        $tourDestinationService = new TourDestinationService($tourDestinationMapper);

        return array(
            'bookingService' => new BookingService($bookingMapper),
            'categoryService' => $categoryService,
            'tourService' => new TourService($tourMapper, $webPageManager, $this->createTourCoverImageManager()),
            'tourDayService' => new TourDayService($tourDayMapper),
            'tourGalleryService' => new TourGalleryService($tourGalleryMapper, $this->createTourGalleryImageManager()),
            'tourReviewService' => new TourReviewService($reviewMapper),
            'tourDateService' => new TourDateService($tourDateMapper),
            'tourDestinationService' => $tourDestinationService,
            'siteService' => new SiteService($categoryService, $tourDestinationService)
        );
    }
}
