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
     * State initialization
     * 
     * @param \Tour\Service\CategoryService $categoryService
     * @return void
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
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
