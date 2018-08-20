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

use Tour\Storage\CategoryMapperInterface;

final class CategoryService
{
    /**
     * Any compliant mapper
     * 
     * @var \Tour\Storage\CategoryMapperInterface
     */
    private $categoryMapper;

    /**
     * State initialization
     * 
     * @param \Tour\Storage\CategoryMapperInterface $categoryMapper
     * @return void
     */
    public function __construct(CategoryMapperInterface $categoryMapper)
    {
        $this->categoryMapper = $categoryMapper;
    }
}
