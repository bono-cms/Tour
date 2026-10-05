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

final class Hotel extends AbstractController
{
    /**
     * Renders a form
     * 
     * @param mixed $hotel
     * @param string $title Page title
     * @return string
     */
    private function createForm($hotel, $title)
    {
        $new = is_object($hotel);

        $id = $new ? false : $hotel[0]->getId();

        // Load preview plugin
        $this->view->getPluginBag()->load('preview');

        // Append breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('Tours', 'Tour:Admin:Grid@indexAction')
                                       ->addOne('Hotels', 'Tour:Admin:Hotel@indexAction')
                                       ->addOne($title);
        // Load plugins
        $this->view->getPluginBag()
                   ->load($this->getWysiwygPluginName());

        return $this->view->render('hotel/form', [
            'new' => $new,
            'hotel' => $hotel,
            'gallery' => !$new ? $this->getModuleService('hotelGalleryService')->fetchAll($id, false) : [],
        ]);
    }

    /**
     * Render all hotels
     * 
     * @return string
     */
    public function indexAction()
    {
        // Append breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('Tours', 'Tour:Admin:Grid@indexAction')
                                       ->addOne('Hotels');

        return $this->view->render('hotel/index', [
            'hotels' => $this->getModuleService('hotelService')->fetchAll(false)
        ]);
    }

    /**
     * Renders add form
     * 
     * @return mixed
     */
    public function addAction()
    {
        // CMS configuration object
        $config = $this->getService('Cms', 'configManager')->getEntity();

        $hotel = new VirtualEntity();
        $hotel->setChangeFreq($config->getSitemapFrequency())
              ->setPriority($config->getSitemapPriority());

        return $this->createForm($hotel, 'Add new hotel');
    }

    /**
     * Renders edit form
     * 
     * @param string $id Hotel id
     * @return mixed
     */
    public function editAction($id)
    {
        $hotel = $this->getModuleService('hotelService')->fetchById($id, true);

        if ($hotel !== false) {
            $name = $this->getCurrentProperty($hotel, 'name');
            return $this->createForm($hotel, $this->translator->translate('Edit the hotel "%s"', $name));
        } else {
            return false;
        }
    }

    /**
     * Deletes a hotel by its ID
     * 
     * @param int $id Hotel id
     * @return int
     */
    public function deleteAction($id)
    {
        $this->getModuleService('hotelService')->deleteById($id);

        $this->flashBag->set('success', 'Selected element has been removed successfully');

        return $this->json([
            'refresh' => true
        ]);
    }

    /**
     * Saves tour day
     * 
     * @return string
     */
    public function saveAction()
    {
        $validator = $this->createValidation();

        $validator->field('hotel.order')
                  ->addRule('numeric');

        $validator->field('translation.*.name')
                  ->required()
                  ->addRule('minlength', null, ['min' => 2]);

        if ($validator->isPassed()) {
            $input = $this->request->getAll();
            $service = $this->getModuleService('hotelService');

            if ($input['data']['hotel']['id']) {
                $service->update($input);

                $this->flashBag->set('success', 'The element has been updated successfully');

                return $this->json([
                    'refresh' => true
                ]);
            } else {
                $id = $service->add($input);

                $this->flashBag->set('success', 'The element has been created successfully');

                return $this->json([
                    'redirect' => $this->createUrl('Tour:Admin:Hotel@editAction', [$id]),
                ]);
            }

        } else {
            return $this->json([
                'errors' => $validator->getErrors()
            ]);
        }
    }
}