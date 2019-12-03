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

use Krystal\Validate\Pattern;
use Krystal\Stdlib\VirtualEntity;
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
     * Leaves a review
     * 
     * @return mixed
     */
    public function reviewAction()
    {
        // Grab POST data
        $input = $this->request->getPost();

        $formValidator = $this->createValidator(array(
            'input' => array(
                'source' => $input,
                'definition' => array(
                    'name' => new Pattern\Name(),
                    'message' => new Pattern\Message()
                )
            )
        ));

        if ($formValidator->isValid()) {
            // Saves a review
            $this->getModuleService('tourReviewService')->save($input);
            
            $this->flashBag->set('success', 'Thank you for your review!');
            return 1;

        } else {
            return $formValidator->getErrors();
        }
    }

    /**
     * Renders booking template
     * 
     * @param int $tourId Tour ID
     * @return string
     */
    public function bookAction($tourId)
    {
        // Find tour entity by its ID
        $tour = $this->getModuleService('tourService')->fetchById($tourId, false);

        if ($tour !== false) {
            $this->loadSitePlugins();

            // Fill amount and product if provided
            $entity = new VirtualEntity();
            $entity->setTitle($this->translator->translate('Book a tour'))
                   ->setTour($tour->getName())
                   ->setTourId($tour->getId())
                   ->setAmount($tour->hasPrice() ? $tour->getPrice() : false);

            return $this->view->render('tour-booking', array(
                'invoice' => $entity,
                'title' => 'New invoice',
                'asClient' => true,
                'page' => $entity,
                'languages' => $this->getService('Cms', 'languageManager')->fetchAll(true)
            ));
        } else {
            return false;
        }
    }

    /**
     * Renders a hotel by its id
     * 
     * @param int $id Hotel id
     * @return string
     */
    public function hotelAction($id)
    {
        $hotelService = $this->getModuleService('hotelService');
        $hotel = $hotelService->fetchById($id, false);

        if ($hotel !== false) {
            // Load global view plugins
            $this->loadSitePlugins();

            // Append image gallery
            $hotel->setGallery($this->getModuleService('hotelGalleryService')->fetchImages($id, 'original'));

            // Append breadcrumbs
            $this->view->getBreadcrumbBag()->addOne($hotel->getName());

            return $this->view->render('tour-hotel', array(
                'hotel' => $hotel,
                'page' => $hotel,
                'languages' => $hotelService->getSwitchUrls($id)
            ));

        } else {
            return false;
        }
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
            // If a tour has constraint language ID
            if ($tour->hasConstraintLanguageId()) {
                if (!$tour->isConstraintLanguageId($this->getService('Cms', 'languageManager')->getCurrentId())) {
                    // Go home
                    $this->response->redirect('/');
                }
            }

            // Set extras
            $tour->setGallery($this->getModuleService('tourGalleryService')->fetchImages($id, 'original'))
                 ->setDays($this->getModuleService('tourDayService')->fetchAll($id, true))
                 ->setDates($this->getModuleService('tourDateService')->fetchByTourId($id))
                 ->setReviews($this->getModuleService('tourReviewService')->fetchAll($id, true))
                 ->setHotels($this->getModuleService('hotelService')->findHotelsByTourId($id))
                 ->setRelatedTours($service->fetchByIds($tour->getRelatedIds()));

            // Load global view plugins
            $this->loadSitePlugins();

            $keeper = $this->createLastCategoryKeeper();

            if ($keeper->hasLastCategoryId()) {
                $category = $this->getModuleService('categoryService')->fetchById($keeper->getLastCategoryId(), false);

                // Append breadcrumbs
                $this->view->getBreadcrumbBag()->addOne($category->getName(), $category->getUrl())
                                               ->addOne($tour->getName());
            }

            $response = $this->view->render('tour-single', array(
                'tour' => $tour,
                'page' => $tour,
                'languages' => $service->getSwitchUrls($id)
            ));

            // Increment view counter
            $service->incrementViewCount($id);

            return $response;

        } else {
            return false;
        }
    }

    /**
     * Performs a search
     * 
     * @return string
     */
    public function searchAction()
    {
        // Load global view plugins
        $this->loadSitePlugins();

        // Append breadcrumb
        $this->view->getBreadcrumbBag()->addOne($this->translator->translate('Search'));

        // Find tours
        $tours = $this->getModuleService('tourService')->filter($this->request->getQuery(), null, null, false, true);

        // Configure page entity
        $page = new VirtualEntity();
        $page->setSeo(false)
             ->setTitle($this->translator->translate('Search results'))
             ->setName($this->translator->translate('Search results') . sprintf(' (%s) ', count($tours)));

        return $this->view->render('tour-category', array(
            'page' => $page,
            'tours' => $tours,
            'category' => $page,
            'languages' => $this->getService('Cms', 'languageManager')->fetchAll(true)
        ));
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
