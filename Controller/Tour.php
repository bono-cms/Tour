<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Tour\Controller;

use Krystal\Form\Gadget\LastCategoryKeeper;
use Site\Controller\AbstractController;

final class Tour extends AbstractController
{
    /**
     * Returns prepared instance of LastCategoryKeeper
     * 
     * @return \Krystal\Form\Gadget\LastCategoryKeeper
     */
    private function createLastCategoryKeeper()
    {
        return new LastCategoryKeeper($this->sessionBag, 'tour_last_category_id', false);
    }

    /**
     * Renders tour template
     * 
     * @param int $id Tour ID
     * @return string
     */
    public function tourAction($id)
    {
        $service = $this->getModuleService('tourService');
        $tour = $service->fetchById($id, false);

        if ($tour !== false) {
            // Load global view plugins
            $this->loadSitePlugins();

            $keeper = $this->createLastCategoryKeeper();

            if ($keeper->hasLastCategoryId()) {
                $category = $this->getModuleService('categoryService')->fetchById($keeper->getLastCategoryId(), false);

                // Append breadcrumbs
                $this->view->getBreadcrumbBag()->addOne($category->getName())
                                               ->addOne($tour->getName());
            }

            return $this->view->render('tour-single', array(
                'tour' => $tour,
                'page' => $tour,
                'languages' => $service->getSwitchUrls($id)
            ));

        } else {
            return false;
        }
    }

    /**
     * Renders category template
     * 
     * @param int $id Category ID
     * @param integer $pageNumber current page number
     * @param string $code Language code
     * @param string $slug Category page's slug
     * @return string
     */
    public function categoryAction($id = false, $pageNumber = 1, $code = null, $slug = null)
    {
        $categoryService = $this->getModuleService('categoryService');
        $tourService = $this->getModuleService('tourService');

        $category = $categoryService->fetchById($id, false);

        if ($category !== false) {
            // Save last category ID
            $this->createLastCategoryKeeper()->persistLastCategoryId($id);

            // Load global view plugins
            $this->loadSitePlugins();

            // Append breadcrumb
            $this->view->getBreadcrumbBag()->addOne($category->getName());

            // Grab all tours filtering by category ID
            $tours = $tourService->fetchAllByCategoryId($id, $pageNumber, 10);
            
            // Prepare pagination
            $paginator = $tourService->getPaginator();
            $this->preparePaginator($paginator, $code, $slug, $pageNumber);

            return $this->view->render('tour-category', array(
                'tours' => $tours,
                'category' => $category,
                'page' => $category,
                'paginator' => $paginator,
                'languages' => $categoryService->getSwitchUrls($id)
            ));

        } else {
            return false;
        }
    }
}
