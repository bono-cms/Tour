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

            return $this->view->disableLayout()->render('gateway', [
                'gateway' => $gateway
            ]);

        } else {
            // Invalid token
            return false;
        }
    }
}
