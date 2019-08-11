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

final class Hotel extends AbstractController
{
    /**
     * Renders form
     * 
     * @param mixed $hotel
     * @return string
     */
    private function createForm($hotel)
    {
        $new = is_object($hotel);

        $id = $new ? false : $hotel[0]->getId();
        
        // Append breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('Tours', 'Tour:Admin:Grid@indexAction')
                                       ->addOne('Hotels', 'Tour:Admin:Hotel@indexAction')
                                       ->addOne($new ? 'Add new hotel' : 'Edit the hotel');
        // Load plugins
        $this->view->getPluginBag()
                   ->load($this->getWysiwygPluginName());

        return $this->view->render('hotel/form', array(
            'new' => $new,
            'hotel' => $hotel,
            'gallery' => !$new ? $this->getModuleService('hotelGalleryService')->fetchAll($id, false) : array(),
        ));
    }

    /**
     * Render all hotels
     * 
     * @return string
     */
    public function indexAction()
    {
        // Append breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('Tours', 'Tour:Admin:Grid@indexAction')
                                       ->addOne('Hotels');

        return $this->view->render('hotel/index', array(
            'hotels' => $this->getModuleService('hotelService')->fetchAll(false)
        ));
    }

    /**
     * Renders add form
     * 
     * @return mixed
     */
    public function addAction()
    {
        return $this->createForm(new VirtualEntity());
    }

    /**
     * Renders edit form
     * 
     * @param string $id Hotel id
     * @return mixed
     */
    public function editAction($id)
    {
        $hotel = $this->getModuleService('hotelService')->fetchById($id, true);

        if ($hotel !== false) {
            return $this->createForm($hotel);
        } else {
            return false;
        }
    }

    /**
     * Deletes a hotel by its ID
     * 
     * @param int $id Hotel id
     * @return int
     */
    public function deleteAction($id)
    {
        $this->getModuleService('hotelService')->deleteById($id);

        $this->flashBag->set('success', 'Selected element has been removed successfully');
        return 1;
    }

    /**
     * Saves tour day
     * 
     * @return string
     */
    public function saveAction()
    {
        $input = $this->request->getPost();

        $service = $this->getModuleService('hotelService');
        $service->save($input);

        if ($input['hotel']['id']) {
            $this->flashBag->set('success', 'The element has been updated successfully');
            return '1';
        } else {
            $this->flashBag->set('success', 'The element has been created successfully');
            return $service->getLastId();
        }
    }
}
