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

final class TourDate extends AbstractController
{
    /**
     * Renders form
     * 
     * @param mixed $date Tour date
     * @return string
     */
    private function createForm($date)
    {
        // Grab ID
        if (is_array($date)) {
            $id = $date[0]['tour_id'];
        } else {
            $id = $date->getTourId();
        }

        // Append breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('Tours', 'Tour:Admin:Grid@indexAction')
                                       ->addOne('Edit the date', $this->createUrl('Tour:Admin:Tour@editAction', array($id)))
                                       ->addOne(!is_array($date) ? 'Add new date' : 'Edit the date');
        // Load plugins
        $this->view->getPluginBag()
                   ->load('datepicker');

        return $this->view->render('tour.date.form', array(
            'date' => $date
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
        $date = new VirtualEntity();
        $date->setTourId($tourId);

        return $this->createForm($date);
    }

    /**
     * Renders edit form
     * 
     * @param string $id Tour date ID
     * @return mixed
     */
    public function editAction($id)
    {
        $date = $this->getModuleService('tourDateService')->fetchById($id);

        if ($date !== false) {
            return $this->createForm($date);
        } else {
            return false;
        }
    }

    /**
     * Deletes tour date by its ID
     * 
     * @param int $id Tour date ID
     * @return int
     */
    public function deleteAction($id)
    {
        $this->getModuleService('tourDateService')->deleteById($id);

        $this->flashBag->set('success', 'Selected element has been removed successfully');
        return 1;
    }

    /**
     * Saves tour date
     * 
     * @return string
     */
    public function saveAction()
    {
        $input = $this->request->getPost('date');

        $service = $this->getModuleService('tourDateService');
        $service->save($input);

        if ($input['id']) {
            $this->flashBag->set('success', 'The element has been updated successfully');
            return '1';
        } else {
            $this->flashBag->set('success', 'The element has been created successfully');
            return $service->getLastId();
        }
    }
}
