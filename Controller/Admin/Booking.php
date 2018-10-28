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

final class Booking extends AbstractController
{
    /**
     * Render all bookings
     * 
     * @return string
     */
    public function indexAction()
    {
        // Append breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('Tours', 'Tour:Admin:Grid@indexAction')
                                       ->addOne('Bookings');

        return $this->view->render('booking.index', array(
            'bookings' => $this->getModuleService('bookingService')->fetchAll()
        ));
    }

    /**
     * Creates shared form
     * 
     * @param mixed $booking
     * @return string
     */
    private function createForm(VirtualEntity $booking)
    {
        // Append breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('Tours', 'Tour:Admin:Grid@indexAction')
                                       ->addOne('Bookings', $this->createUrl('Tour:Admin:Booking@indexAction'))
                                       ->addOne(!$booking->getId() ? 'Add new booking' : 'Edit the booking');
                                       
        return $this->view->render('booking.form', array(
            'booking' => $booking
        ));
    }

    /**
     * Renders add form
     * 
     * @return mixed
     */
    public function addAction()
    {
        return $this->createForm(new VirtualEntity);
    }

    /**
     * Renders edit form
     * 
     * @param int $id
     * @return mixed
     */
    public function editAction($id)
    {
        $booking = $this->getModuleService('bookingService')->fetchById($id);

        if ($booking !== false) {
            return $this->createForm($booking);
        } else {
            return false;
        }
    }

    /**
     * Saves booking
     * 
     * @return mixed
     */
    public function saveAction()
    {
        $input = $this->request->getPost('booking');

        $service = $this->getModuleService('bookingService');
        $service->save($input);

        if ($input['id']) {
            $this->flashBag->set('success', 'The element has been updated successfully');
            return 1;
            
        } else {
            $this->flashBag->set('success', 'The element has been created successfully');
            return $service->getLastId();
        }
    }

    /**
     * Deletes booking by its ID
     * 
     * @param int $id Booking ID
     * @return mixed
     */
    public function deleteAction($id)
    {
        $this->getModuleService('bookingService')->deleteById($id);
        $this->flashBag->set('success', 'Selected element has been removed successfully');
        return 1;
    }
}
