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
use Tour\Service\HotelService;
use Tour\Service\HotelGalleryService;
use Tour\Service\TourPricePolicyService;

final class Module extends AbstractCmsModule
{
    /**
     * Builds gallery image manager service
     * 
     * @return \Krystal\Image\Tool\ImageManager
     */
    private function createHotelImageManager()
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
            '/data/uploads/module/tour/hotels/covers/',
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
    private function createHotelGalleryImageManager()
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
            '/data/uploads/module/tour/hotels/gallery/',
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
        $bookingGuestMapper = $this->getMapper('/Tour/Storage/MySQL/TourBookingGuestMapper');
        $reviewMapper = $this->getMapper('/Tour/Storage/MySQL/TourReviewMapper');
        $hotelMapper = $this->getMapper('/Tour/Storage/MySQL/HotelMapper');
        $hotelGalleryMapper = $this->getMapper('/Tour/Storage/MySQL/HotelGalleryMapper');
        $policyMapper = $this->getMapper('/Tour/Storage/MySQL/TourPricePolicyMapper');

        $webPageManager = $this->getWebPageManager();

        // Category service
        $categoryService = new CategoryService($categoryMapper, $webPageManager, $this->createCategoryImageManager());
        $tourDestinationService = new TourDestinationService($tourDestinationMapper);
        $tourService = new TourService($tourMapper, $webPageManager, $this->createTourCoverImageManager());

        return array(
            'tourPricePolicyService' => new TourPricePolicyService($policyMapper),
            'hotelService' => new HotelService($hotelMapper, $webPageManager, $this->createHotelImageManager()),
            'hotelGalleryService' => new HotelGalleryService($hotelGalleryMapper, $this->createHotelGalleryImageManager()),
            'bookingService' => new BookingService($bookingMapper, $bookingGuestMapper),
            'categoryService' => $categoryService,
            'tourService' => $tourService,
            'tourDayService' => new TourDayService($tourDayMapper),
            'tourGalleryService' => new TourGalleryService($tourGalleryMapper, $this->createTourGalleryImageManager()),
            'tourReviewService' => new TourReviewService($reviewMapper),
            'tourDateService' => new TourDateService($tourDateMapper),
            'tourDestinationService' => $tourDestinationService,
            'siteService' => new SiteService($categoryService, $tourDestinationService, $tourService)
        );
    }
}
