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
     * Notify client about payment
     * 
     * @param string $token
     * @return mixed
     */
    public function notifyAction($token)
    {
        // Find invoice by its token
        $invoice = $this->getModuleService('bookingService')->findByToken($token);

        if ($invoice) {
            $params = array_merge($invoice->getProperties(), array(
                'link' => $this->request->getBaseUrl() . $this->createUrl('Tour:Payment@gatewayAction', array($invoice['token']))
            ));

            // Create email body
            $body = $this->view->renderRaw('Tour', 'mail', 'notify', $params);

            // Now send it
            $this->getService('Cms', 'mailer')->sendTo($invoice['email'], $this->translator->translate('Please confirm payment'), $body);

            $this->flashBag->set('success', $this->translator->translate('Notification to %s has been successfully sent', $invoice['email']));
            $this->response->back();

        } else {
            // Invalid token
            return false;
        }
    }

    /**
     * Render all bookings
     * 
     * @param int $page Current page number
     * @return string
     */
    public function indexAction($page)
    {
        if (!$page) {
            $page = 1;
        }

        // Append breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('Tours', 'Tour:Admin:Grid@indexAction')
                                       ->addOne('Bookings');
        // Grab the service
        $bookingService = $this->getModuleService('bookingService');

        // Configure paginator
        $paginator = $bookingService->getPaginator();
        $paginator->setUrl($this->createUrl('Tour:Admin:Booking@indexAction'));

        return $this->view->render('booking.index', array(
            'bookings' => $bookingService->fetchAll($page, $this->getSharedPerPageCount()),
            'paginator' => $paginator
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
                                       ->addOne('Bookings', $this->createUrl('Tour:Admin:Booking@indexAction', array(null)))
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
        // Booking service
        $service = $this->getModuleService('bookingService');

        // Batch removal
        if ($this->request->isPost()) {
            if ($this->request->hasPost('batch')) {
                $ids = $this->request->getPost('batch');
                // Delete bookings by their IDs
                $service->deleteByIds($ids);
                $this->flashBag->set('success', 'Selected elements have been removed successfully');
            } else {
                $this->flashBag->set('warning', 'You should select at least one element to remove');
            }
        }

        // Single removal
        if (!empty($id)) {
            $service->deleteById($id);
            $this->flashBag->set('success', 'Selected element has been removed successfully');
        }

        return 1;
    }
}
