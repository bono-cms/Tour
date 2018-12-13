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

use Krystal\Stdlib\VirtualEntity;
use Cms\Controller\Admin\AbstractController;

final class TourDestination extends AbstractController
{
    /**
     * Render destinations
     * 
     * @return string
     */
    public function indexAction()
    {
        // Append breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('Tours', 'Tour:Admin:Grid@indexAction')
                                       ->addOne('Tour destinations');

        return $this->view->render('tour.destination.grid', array(
            'destinations' => $this->getModuleService('tourDestinationService')->fetchAll(false)
        ));
    }

    /**
     * Create destination form
     * 
     * @param \Krystal\Stdlib\VirtualEntity|array $destination
     * @return string
     */
    private function createForm($destination)
    {
        // Whether this destination is new
        $new = is_object($destination);

        // Append breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('Tours', 'Tour:Admin:Grid@indexAction')
                                       ->addOne('Tour destinations', 'Tour:Admin:TourDestination@indexAction')
                                       ->addOne($new ? 'Add new tour destination' : 'Update tour destination');

        return $this->view->render('tour.destination.form', array(
            'destination' => $destination,
            'new' => $new
        ));
    }

    /**
     * Renders add form
     * 
     * @return string
     */
    public function addAction()
    {
        return $this->createForm(new VirtualEntity);
    }

    /**
     * Renders edit form
     * 
     * @param int $id Tour destination ID
     * @return string
     */
    public function editAction($id)
    {
        $destination = $this->getModuleService('tourDestinationService')->fetchById($id, true);

        if ($destination) {
            return $this->createForm($destination);
        } else {
            return false;
        }
    }

    /**
     * Deletes tour destination by its ID
     * 
     * @param int $id Tour destination ID
     * @return int
     */
    public function deleteAction($id)
    {
        $this->getModuleService('tourDestinationService')->deleteById($id);

        $this->flashBag->set('success', 'Selected element has been removed successfully');
        return 1;
    }

    /**
     * Saves tour destination
     * 
     * @return int
     */
    public function saveAction()
    {
        // Raw request data
        $input = $this->request->getPost();

        $tourDestinationService = $this->getModuleService('tourDestinationService');
        $tourDestinationService->save($input);

        if ($input['destination']['id']) {
            $this->flashBag->set('success', 'The element has been updated successfully');
            return 1;
        } else {
            $this->flashBag->set('success', 'The element has been created successfully');
            return $tourDestinationService->getLastId();
        }
    }
}
