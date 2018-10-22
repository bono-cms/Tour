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
     * @return string
     */
    private function createForm($entity)
    {
        // Grab ID
        if (is_array($entity)) {
            $id = $entity[0]['id'];
        } else {
            $id = null;
        }

        // Append breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('Tours', 'Tour:Admin:Grid@indexAction')
                                       ->addOne(!is_array($entity) ? 'Add a tour' : 'Edit the tour');
        // Load plugins
        $this->view->getPluginBag()
                   ->load($this->getWysiwygPluginName());

        return $this->view->render('tour.form', array(
            'tour' => $entity,
            'days' => $id !== null ? $this->getModuleService('tourDayService')->fetchAll($id, false) : array(),
            'gallery' => $id !== null ? $this->getModuleService('tourGalleryService')->fetchAll($id, false) : array()
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
            return $this->createForm($tour);
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
        $tour = new VirtualEntity();
        $tour->setSeo(true);

        return $this->createForm($tour);
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
            if ($service->update($input)) {
                $this->flashBag->set('success', 'The element has been updated successfully');
                return '1';
            }

        } else {
            if ($service->add($input)) {
                $this->flashBag->set('success', 'The element has been created successfully');
                return $service->getLastId();
            }
        }
    }

    /**
     * Deletes a tour
     * 
     * @param int $id
     * @return string
     */
    public function deleteAction($id)
    {
        $service = $this->getModuleService('tourService');
        $service->deleteById($id);

        $this->flashBag->set('success', 'Selected element has been removed successfully');
        return 1;
    }
}
