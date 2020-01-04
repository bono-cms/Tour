<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Tour\Controller\Admin;

use Cms\Controller\Admin\AbstractController;
use Krystal\Stdlib\VirtualEntity;
use Tour\Service\CategoryService;

final class Grid extends AbstractController
{
    /**
     * Renders grid
     * 
     * @return string
     */
    public function indexAction()
    {
        // Append breadcrumb
        $this->view->getBreadcrumbBag()
                   ->addOne('Tours');

        // Grab tour service
        $tourService = $this->getModuleService('tourService');

        return $this->view->render('grid', array(
            'categories' => $this->getModuleService('categoryService')->fetchAll(),
            'categoryList' => $this->getModuleService('categoryService')->fetchList(),
            'tours' => $this->getFilter($tourService),
            'paginator' => $tourService->getPaginator(),
            'newReviews' => $this->getModuleService('tourReviewService')->countUnpublished()
        ));
    }
}
