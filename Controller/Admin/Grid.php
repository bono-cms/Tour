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

        // Current URL params
        $params = array_merge($this->request->getQuery(), array('page' => '(:var)'));

        $url = $this->urlBuilder->createQueryUrl('Tour:Admin:Grid@indexAction', $params, 0);

        // Configure pagination
        $paginator = $tourService->getPaginator();
        $paginator->setUrl($url);

        $tours = $this->getFilter($tourService, $url);

        return $this->view->render('grid', array(
            'categories' => $this->getModuleService('categoryService')->fetchList(false),
            'tours' => $tours,
            'paginator' => $paginator
        ));
    }
}
