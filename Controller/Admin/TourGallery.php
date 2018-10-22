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

final class TourGallery extends AbstractController
{
    /**
     * Renders form
     * 
     * @param mixed $day Tour image
     * @return string
     */
    private function createForm($image)
    {
        // Grab ID
        if (is_array($image)) {
            $id = $image[0]['tour_id'];
        } else {
            $id = $image->getTourId();
        }

        // Append breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('Tours', 'Tour:Admin:Grid@indexAction')
                                       ->addOne('Edit the tour', $this->createUrl('Tour:Admin:Tour@editAction', array($id)))
                                       ->addOne(!is_array($image) ? 'Add new image' : 'Edit the image');
        // Load plugins
        $this->view->getPluginBag()
                   ->load('preview');

        return $this->view->render('tour.gallery.form', array(
            'image' => $image
        ));
    }

    /**
     * Renders add form
     * 
     * @param int $tourId
     * @return mixed
     */
    public function addAction($tourId)
    {
        $image = new VirtualEntity();
        $image->setTourId($tourId);

        return $this->createForm($image);
    }

    /**
     * Renders edit form
     * 
     * @param string $id Tour image ID
     * @return mixed
     */
    public function editAction($id)
    {
        $image = $this->getModuleService('tourGalleryService')->fetchById($id);

        if ($image !== false) {
            return $this->createForm($image);
        } else {
            return false;
        }
    }

    /**
     * Deletes tour image by its ID
     * 
     * @param int $id Tour image ID
     * @return int
     */
    public function deleteAction($id)
    {
        $this->getModuleService('tourGalleryService')->deleteById($id);

        $this->flashBag->set('success', 'Selected element has been removed successfully');
        return 1;
    }

    /**
     * Saves tour image
     * 
     * @return string
     */
    public function saveAction()
    {
        $input = $this->request->getPost('image');
        $service = $this->getModuleService('tourGalleryService');

        if ($input['id']) {
            $service->update($input);
            $this->flashBag->set('success', 'The element has been updated successfully');
            return '1';
        } else {
            $service->add($input);
            $this->flashBag->set('success', 'The element has been created successfully');
            return $service->getLastId();
        }
    }
}
