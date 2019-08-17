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
     * Renders a form
     * 
     * @param mixed $hotel
     * @param string $title Page title
     * @return string
     */
    private function createForm($hotel, $title)
    {
        $new = is_object($hotel);

        $id = $new ? false : $hotel[0]->getId();

        // Load preview plugin
        $this->view->getPluginBag()->load('preview');

        // Append breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('Tours', 'Tour:Admin:Grid@indexAction')
                                       ->addOne('Hotels', 'Tour:Admin:Hotel@indexAction')
                                       ->addOne($title);
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
        return $this->createForm(new VirtualEntity(), 'Add new hotel');
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
            $name = $this->getCurrentProperty($hotel, 'name');
            return $this->createForm($hotel, $this->translator->translate('Edit the hotel "%s"', $name));
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
        $input = $this->request->getAll();
        $service = $this->getModuleService('hotelService');

        if ($input['data']['hotel']['id']) {
            $service->update($input);
            
            $this->flashBag->set('success', 'The element has been updated successfully');
            return '1';
        } else {
            $id = $service->add($input);

            $this->flashBag->set('success', 'The element has been created successfully');
            return $id;
        }
    }
}
