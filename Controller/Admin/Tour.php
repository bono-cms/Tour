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

final class Tour extends AbstractController
{
    /**
     * Creates form
     * 
     * @param mixed $entity
     * @param string $title Page title
     * @return string
     */
    private function createForm($entity, $title)
    {
        $new = is_object($entity);
        $id = $new ? null : $entity[0]['id'];

        // Append breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('Tours', 'Tour:Admin:Grid@indexAction')
                                       ->addOne($title);
        // Load plugins
        $this->view->getPluginBag()
                   ->load(array($this->getWysiwygPluginName(), 'chosen', 'preview'));

        return $this->view->render('tour.form', array(
            'new' => $new,
            'tour' => $entity,
            'days' => !$new ? $this->getModuleService('tourDayService')->fetchAll($id, false) : array(),
            'dates' => !$new ? $this->getModuleService('tourDateService')->fetchByTourId($id) : array(),
            'gallery' => !$new ? $this->getModuleService('tourGalleryService')->fetchAll($id, false) : array(),
            'reviews' => !$new ? $this->getModuleService('tourReviewService')->fetchAll($id, false) : array(),
            'prices' => !$new ? $this->getModuleService('tourPricePolicyService')->fetchAll($id) : array(),
            'categories' => $this->getModuleService('categoryService')->fetchList(true),
            'tours' => $this->getModuleService('tourService')->fetchList(array($id)),
            'destinations' => $this->getModuleService('tourDestinationService')->fetchList(),
            'hotels' => $this->getModuleService('hotelService')->fetchList()
        ));
    }

    /**
     * Renders edit form
     * 
     * @param int $id Tour ID
     * @return string
     */
    public function editAction($id)
    {
        $tour = $this->getModuleService('tourService')->fetchById($id, true);

        if ($tour !== false) {
            $name = $this->getCurrentProperty($tour, 'name');
            return $this->createForm($tour, $this->translator->translate('Edit the tour "%s"', $name));
        } else {
            return false;
        }
    }

    /**
     * Renders empty adding form
     * 
     * @return string
     */
    public function addAction()
    {
        // CMS configuration object
        $config = $this->getService('Cms', 'configManager')->getEntity();

        $tour = new VirtualEntity();
        $tour->setSeo(true)
             ->setPublished(true)
             ->setChangeFreq($config->getSitemapFrequency())
             ->setPriority($config->getSitemapPriority());

        return $this->createForm($tour, 'Add a tour');
    }

    /**
     * Saves a tour
     * 
     * @return string
     */
    public function saveAction()
    {
        $input = $this->request->getPost();

        $service = $this->getModuleService('tourService');

        if (!empty($input['tour']['id'])) {
            if ($service->update($this->request->getAll())) {
                $this->flashBag->set('success', 'The element has been updated successfully');
                return '1';
            }

        } else {
            if ($id = $service->add($this->request->getAll())) {
                $this->flashBag->set('success', 'The element has been created successfully');
                return $id;
            }
        }
    }

    /**
     * Deletes a tour (or batch)
     * 
     * @param int $id Tour ID
     * @return int
     */
    public function deleteAction($id)
    {
        $service = $this->getModuleService('tourService');

        // Batch removal
        if ($this->request->hasPost('batch')) {
            $ids = array_keys($this->request->getPost('batch'));

            $service->delete($ids);
            $this->flashBag->set('success', 'Selected elements have been removed successfully');

        } else {
            $this->flashBag->set('warning', 'You should select at least one element to remove');
        }

        // Single removal
        if (!empty($id)) {
            $service->delete($id);
            $this->flashBag->set('success', 'Selected element has been removed successfully');
        }

        return 1;
    }
}
