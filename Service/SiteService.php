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
     * Tour service instance
     * 
     * @var \Tour\Service\TourService
     */
    private $tourService;

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
     * @param \Tour\Service\TourService $tourService
     * @return void
     */
    public function __construct(CategoryService $categoryService, TourDestinationService $tourDestinationService, TourService $tourService)
    {
        $this->categoryService = $categoryService;
        $this->tourDestinationService = $tourDestinationService;
        $this->tourService = $tourService;
    }

    /**
     * Returns basic information (can be used for sidebar)
     * 
     * @param mixed $excludedId
     * @return array
     */
    public function getBasic($excludedId = null)
    {
        return $this->tourService->fetchBasic($excludedId);
    }

    /**
     * Returns recommended tour entities
     * 
     * @param int $limit Optional limit
     * @return array
     */
    public function getRecommended($limit = null)
    {
        return $this->tourService->fetchRecommended(null, $limit);
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
     * @param boolean $all Whether to fetch all records or a hashmap only
     * @return array
     */
    public function getCategories($all = true)
    {
        return $all ? $this->categoryService->fetchAll() : $this->categoryService->fetchList();
    }
}
