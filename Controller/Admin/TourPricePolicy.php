<?php

/**
 * This file is part of the Bono CMS
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Tour\Controller\Admin;

use Cms\Controller\Admin\AbstractController;
use Krystal\Stdlib\VirtualEntity;

final class TourPricePolicy extends AbstractController
{
    /**
     * Creates a form
     * 
     * @param \Krystal\Stdlib\VirtualEntity $policy
     * @param string $title Page title
     * @return string
     */
    private function createForm(VirtualEntity $policy, $title)
    {
        $tour = $this->getModuleService('tourService')->fetchById($policy->getTourId(), false);

        $this->view->getBreadcrumbBag()->addOne('Tours', 'Tour:Admin:Grid@indexAction')
                                       ->addOne($this->translator->translate('Edit the tour "%s"', $tour->getName()), $this->createUrl('Tour:Admin:Tour@editAction', [$policy->getTourId()]))
                                       ->addOne($title);
        
        return $this->view->render('tour.policy.form', [
            'policy' => $policy
        ]);
    }

    /**
     * Renders add form
     * 
     * @param int $tourId Current tour id
     * @return string
     */
    public function addAction($tourId)
    {
        $policy = new VirtualEntity();
        $policy->setTourId($tourId);

        return $this->createForm($policy, 'Add new price policy');
    }

    /**
     * Renders edit form
     * 
     * @param int $id Policy id
     * @return string
     */
    public function editAction($id)
    {
        $policy = $this->getModuleService('tourPricePolicyService')->fetchById($id);

        if ($policy) {
            // Save this old attribute
            $this->formAttribute->setOldAttribute('qty', $policy->getQty());

            return $this->createForm($policy, 'Edit the price policy');
        } else {
            return false;
        }
    }

    /**
     * Deletes a policy
     * 
     * @param int $id Policy id
     * @return int
     */
    public function deleteAction($id)
    {
        $this->getModuleService('tourPricePolicyService')->deleteById($id);

        $this->flashBag->set('success', 'Price policy has been removed successfully');

        return $this->json([
            'refresh' => true
        ]);
    }

    /**
     * Saves policy
     * 
     * @return int
     */
    public function saveAction()
    {
        $input = $this->request->getPost('policy');

        $qtyChanged = $this->formAttribute->hasChanged('qty', $input['qty']) 
            ? $this->getModuleService('tourPricePolicyService')->hasQty($input['tour_id'], $input['qty']) : false;

        $validator = $this->createValidation();

        $validator->setFieldRule('unique_qty', function($value) use ($qtyChanged) {
            return !$qtyChanged;
        }, 'A price for this number of people already defined');

        $validator->field('policy.qty')
                  ->required()
                  ->addRule('unique_qty');

        $validator->field('policy.price')
                  ->required()
                  ->addRule('numeric')
                  ->addRule('positive');

        if ($validator->isPassed()) {
            $policyService = $this->getModuleService('tourPricePolicyService');
            $policyService->save($input);

            if ($input['id']) {
                $this->flashBag->set('success', 'Price policy has been updated successfully');

                return $this->json([
                    'refresh' => true
                ]);
            } else {
                $this->flashBag->set('success', 'Price policy has been added successfully');

                return $this->json([
                    'redirect' => $this->createUrl('Tour:Admin:TourPricePolicy@editAction', [$policyService->getLastId()]),
                ]);
            }

        } else {
            return $this->json([
                'errors' => $validator->getErrors()
            ]);
        }
    }
}