<?php

/**
 * This file is part of the Bono CMS
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
            $params = array_merge($invoice->getProperties(), [
                'link' => $this->request->getBaseUrl() . $this->createUrl('Tour:Payment@gatewayAction', [$invoice['token']])
            ]);

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

        return $this->view->render('booking/index', [
            'bookings' => $bookingService->fetchAll($page, $this->getSharedPerPageCount()),
            'paginator' => $paginator
        ]);
    }

    /**
     * Creates shared form
     * 
     * @param \Krystal\Stdlib\VirtualEntity $booking
     * @return string
     */
    private function createForm(VirtualEntity $booking)
    {
        // Append breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('Tours', 'Tour:Admin:Grid@indexAction')
                                       ->addOne('Bookings', $this->createUrl('Tour:Admin:Booking@indexAction', [null]))
                                       ->addOne(!$booking->getId() ? 'Add new booking' : $this->translator->translate('Edit the booking of "%s"', $booking->getClient()));

        return $this->view->render('booking/form', [
            'booking' => $booking
        ]);
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
        $validator = $this->createValidation();

        $validator->field('booking.tour')
                  ->required();

        $validator->field('booking.client')
                  ->required()
                  ->addRule('minlength', null, ['min' => 2]);

        $validator->field('booking.email')
                  ->required()
                  ->addRule('email');

        $validator->field('booking.phone')
                  ->required();

        $validator->field('booking.amount')
                  ->required()
                  ->addRule('numeric');

        if ($validator->isPassed()) {
            $input = $this->request->getPost('booking');

            $service = $this->getModuleService('bookingService');
            $service->save($input);

            if ($input['id']) {
                $this->flashBag->set('success', 'The element has been updated successfully');

                return $this->json([
                    'refresh' => true
                ]);

            } else {
                $this->flashBag->set('success', 'The element has been created successfully');

                return $this->json([
                    'redirect' => $this->createUrl('Tour:Admin:Booking@editAction', [$service->getLastId()]),
                ]);
            }

        } else {
            return $this->json([
                'errors' => $validator->getErrors()
            ]);
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

        return $this->json([
            'refresh' => true
        ]);
    }
}