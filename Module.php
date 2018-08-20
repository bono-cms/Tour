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

use Cms\AbstractCmsModule;
use Tour\Service\CategoryService;
use Tour\Service\TourService;

final class Module extends AbstractCmsModule
{
    /**
     * {@inheritDoc}
     */
    public function getServiceProviders()
    {
        $categoryMapper = $this->getMapper('/Tour/Storage/MySQL/CategoryMapper');
        $tourMapper = $this->getMapper('/Tour/Storage/MySQL/TourMapper');

        $webPageManager = $this->getWebPageManager();

        return array(
            'categoryService' => new CategoryService($categoryMapper, $webPageManager),
            'tourService' => new TourService($tourMapper, $webPageManager)
        );
    }
}
