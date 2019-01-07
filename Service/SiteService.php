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

final class SiteService
{
    /**
     * Any compliant category service
     * 
     * @var \Tour\Service\CategoryService
     */
    private $categoryService;

    /**
     * Tour destination service
     * 
     * @var \Tour\Service\TourDestinationService
     */
    private $tourDestinationService;

    /**
     * State initialization
     * 
     * @param \Tour\Service\CategoryService $categoryService
     * @param \Tour\Service\TourDestinationService $tourDestinationService
     * @return void
     */
    public function __construct(CategoryService $categoryService, TourDestinationService $tourDestinationService)
    {
        $this->categoryService = $categoryService;
        $this->tourDestinationService = $tourDestinationService;
    }

    /**
     * Returns an array of destinations
     * 
     * @return array
     */
    public function getDestinations()
    {
        return $this->tourDestinationService->fetchList();
    }

    /**
     * Returns a hashmap of categories
     * 
     * @return array
     */
    public function getCategories()
    {
        return $this->categoryService->fetchList(true);
    }
}
