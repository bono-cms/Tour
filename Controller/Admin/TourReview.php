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

final class TourReview extends AbstractController
{
    /**
     * Approves a review by its ID
     * 
     * @param int $reviewId
     * @return void
     */
    public function approveAction($reviewId)
    {
        // Approve a review by its ID
        $this->getModuleService('tourReviewService')->approveById($reviewId);

        $this->flashBag->set('success', 'Selected review has been approved. Now its visible on site');

        // And redirect back
        $this->response->back();
    }

    /**
     * Render all reviews
     * 
     * @param int $page Current page number
     * @return string
     */
    public function indexAction($page)
    {
        if (!$page) {
            $page = 1;
        }

        // Append breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('Tours', 'Tour:Admin:Grid@indexAction')
                                       ->addOne('Reviews');
        
        $service = $this->getModuleService('tourReviewService');

        // Fetch all reviews
        $reviews = $service->fetchAll(null, false, $page, $this->getSharedPerPageCount());

        // Configure pagination instance
        $paginator = $service->getPaginator();
        $paginator->setUrl($this->createUrl('Tour:Admin:TourReview@indexAction'));

        return $this->view->render('reviews', array(
            'reviews' => $reviews,
            'paginator' => $paginator
        ));
    }

    /**
     * Deletes a review
     * 
     * @param int $id Review ID
     * @return mixed
     */
    public function deleteAction($id)
    {
        $this->getModuleService('tourReviewService')->deleteById($id);

        $this->flashBag->set('success', 'Selected element has been removed successfully');
        return 1;
    }
}
