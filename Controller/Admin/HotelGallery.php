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

final class HotelGallery extends AbstractController
{
    /**
     * Renders a form
     * 
     * @param \Krystal\Stdlib\VirtualEntity $photo
     * @param string $title Page title
     * @return mixed
     */
    private function createForm(VirtualEntity $image, $title)
    {
        // Load view plugins
        $this->view->getPluginBag()->load('preview');

        // Grab current hotel
        $hotel = $this->getModuleService('hotelService')->fetchById($image->getHotelId(), false);

        if ($hotel !== false) {
            // Append breadcrumbs
            $this->view->getBreadcrumbBag()->addOne('Tours', 'Tour:Admin:Grid@indexAction')
                                           ->addOne('Hotels', 'Tour:Admin:Hotel@indexAction')
                                           ->addOne($this->translator->translate('Edit the hotel "%s"', $hotel->getName()), $this->createUrl('Tour:Admin:Hotel@editAction', array($image->getHotelId())))
                                           ->addOne($title);

            return $this->view->render('hotel/gallery.form', array(
                'image' => $image
            ));
        } else {
            // Wrong hotel id supplied
            return false;
        }
    }

    /**
     * Renders adding form
     * 
     * @param int $hotelId
     * @return string
     */
    public function addAction($hotelId)
    {
        $photo = new VirtualEntity;
        $photo->setHotelId($hotelId);

        return $this->createForm($photo, 'Add new image');
    }

    /**
     * Renders image form
     * 
     * @param int $id Image id
     * @return mixed
     */
    public function editAction($id)
    {
        $image = $this->getModuleService('hotelGalleryService')->fetchById($id);

        if ($image !== false) {
            return $this->createForm($image, 'Edit an image');
        } else {
            return false;
        }
    }

    /**
     * Deletes an image by its id
     * 
     * @return string
     */
    public function deleteAction($id)
    {
        $this->getModuleService('hotelGalleryService')->deleteById($id);

        $this->flashBag->set('success', 'Selected element has been removed successfully');
        return 1;
    }

    /**
     * Saves an image
     * 
     * @return mixed
     */
    public function saveAction()
    {
        $input = $this->request->getPost('image');
        $service = $this->getModuleService('hotelGalleryService');

        if ($input['id']) {
            $service->update($this->request->getAll());

            $this->flashBag->set('success', 'The element has been updated successfully');
            return '1';
        } else {
            $service->add($this->request->getAll());

            $this->flashBag->set('success', 'The element has been created successfully');
            return $service->getLastId();
        }
    }
}
