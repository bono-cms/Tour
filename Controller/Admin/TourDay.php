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

final class TourDay extends AbstractController
{
    /**
     * Renders form
     * 
     * @param mixed $day Tour day
     * @return string
     */
    private function createForm($day)
    {
        $tour = $this->getModuleService('tourService')->fetchById($day->getTourId(), false);

        if ($tour !== false) {
            // Grab ID
            if (is_array($day)) {
                $id = $day[0]['tour_id'];
            } else {
                $id = $day->getTourId();
            }

            // Append breadcrumbs
            $this->view->getBreadcrumbBag()->addOne('Tours', 'Tour:Admin:Grid@indexAction')
                                           ->addOne($this->translator->translate('Edit the tour "%s"', $tour->getName()), $this->createUrl('Tour:Admin:Tour@editAction', array($id)))
                                           ->addOne(!is_array($day) ? 'Add new day' : 'Edit the day');
            // Load plugins
            $this->view->getPluginBag()
                       ->load($this->getWysiwygPluginName());

            return $this->view->render('tour.day.form', array(
                'day' => $day
            ));
        } else {
            return false;
        }
    }

    /**
     * Renders add form
     * 
     * @param int $tourId
     * @return mixed
     */
    public function addAction($tourId)
    {
        $day = new VirtualEntity();
        $day->setTourId($tourId);

        return $this->createForm($day);
    }

    /**
     * Renders edit form
     * 
     * @param string $id Tour day ID
     * @return mixed
     */
    public function editAction($id)
    {
        $day = $this->getModuleService('tourDayService')->fetchById($id, true);

        if ($day !== false) {
            return $this->createForm($day);
        } else {
            return false;
        }
    }

    /**
     * Deletes tour day by its ID
     * 
     * @param int $id Tour day ID
     * @return int
     */
    public function deleteAction($id)
    {
        $this->getModuleService('tourDayService')->deleteById($id);

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

        $service = $this->getModuleService('tourDayService');
        $service->save($input);

        if ($input['day']['id']) {
            $this->flashBag->set('success', 'The element has been updated successfully');
            return '1';
        } else {
            $this->flashBag->set('success', 'The element has been created successfully');
            return $service->getLastId();
        }
    }
}
