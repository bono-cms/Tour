<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Tour\Controller;

use Krystal\Validate\Pattern;
use Site\Controller\AbstractController;
use Tour\Gateway\GatewayService;

final class Payment extends AbstractController
{
    /**
     * {@inheritDoc}
     */
    protected function bootstrap($action)
    {
        // Disabled CSRF for gateway action
        if ($action === 'successAction') {
            $this->enableCsrf = false;
        }

        parent::bootstrap($action);

        // Configure view
        $this->view->setModule('Tour')
                   ->disableLayout()
                   ->setTheme('payment');
    }

    /**
     * Renders booking page
     * 
     * @return mixed
     */
    public function invoiceAction()
    {
        $data = $this->request->getPost();

        // Build form validator
        $formValidator = $this->createValidator(array(
            'input' => array(
                'source' => $data,
                'definition' => array(
                    'client' => new Pattern\Name(),
                    'tour' => new Pattern\Name(),
                    'email' => new Pattern\Email(),
                    'phone' => new Pattern\Phone(),
                    'captcha' => new Pattern\Captcha($this->captcha)
                )
            )
        ));

        if ($formValidator->isValid()) {
            // Create email body
            $body = $this->view->renderRaw('Tour', 'mail', 'new', $data);

            // Now send it
            $this->getService('Cms', 'mailer')->send($this->translator->translate('Your have a new invoice'), $body);

            // Add now and get last token
            $token = $this->getModuleService('bookingService')->save($data);

            // If amount not provided, then update
            if (!isset($data['amount'])) {
                $this->flashBag->set('success', 'Thanks! Your invoice has been sent');
                return '1';
            } else {
                // Otherwise redirect to payment page
                return $this->json([
                    'url' => $this->request->getBaseUrl() . $this->createUrl('Tour:Payment@gatewayAction', array($token))
                ]);
            }

        } else {
            return $formValidator->getErrors();
        }
    }

    /**
     * Handle success or failure after payment gets done
     * 
     * @param string $token
     * @return mixed
     */
    public function successAction($token)
    {
        // Make sure they didn't press Cancel button
        if (GatewayService::transactionFailed()) {
            return $this->view->render('cancel');
        }

        $success = $this->getModuleService('bookingService')->confirmPayment($token);

        if ($success) {
            return $this->view->render('success');
        }
    }

    /**
     * Renders gateway
     * 
     * @param string $token
     * @return string
     */
    public function gatewayAction($token)
    {
        // Find invoice by its token
        $invoice = $this->getModuleService('bookingService')->findByToken($token);

        if ($invoice) {
            // Create back URL
            $backUrl = $this->request->getBaseUrl() . $this->createUrl('Tour:Payment@successAction', array($token));
            $gateway = GatewayService::factory($invoice['id'], $invoice['amount'], $backUrl);

            return $this->view->render('gateway', array(
                'gateway' => $gateway
            ));

        } else {
            // Invalid token
            return false;
        }
    }
}
