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

final class Category extends AbstractController
{
    /**
     * Renders category form
     * 
     * @param mixed $entity
     * @param string $title Page title
     * @return string
     */
    private function createForm($entity, $title)
    {
        // Append breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('Tours', 'Tour:Admin:Grid@indexAction')
                                       ->addOne($title);
        // Load plugins
        $this->view->getPluginBag()
                   ->load(array($this->getWysiwygPluginName(), 'preview'));

        return $this->view->render('category.form', array(
            'category' => $entity
        ));
    }

    /**
     * Renders empty form
     * 
     * @return string
     */
    public function addAction()
    {
        // CMS configuration object
        $config = $this->getService('Cms', 'configManager')->getEntity();

        $category = new VirtualEntity();
        $category->setSeo(true) // Make SEO checked by default
                 ->setChangeFreq($config->getSitemapFrequency())
                 ->setPriority($config->getSitemapPriority());

        return $this->createForm($category, 'Add a category');
    }

    /**
     * Renders edit form
     * 
     * @param int $id Category ID
     * @return string
     */
    public function editAction($id)
    {
        $category = $this->getModuleService('categoryService')->fetchById($id, true);

        if ($category !== false) {
            $name = $this->getCurrentProperty($category, 'name');
            return $this->createForm($category, $this->translator->translate('Edit the category "%s"', $name));
        } else {
            return false;
        }
    }

    /**
     * Deletes a category
     * 
     * @param int $id
     * @return string
     */
    public function deleteAction($id)
    {
        $service = $this->getModuleService('categoryService');
        $service->deleteById($id);

        $this->flashBag->set('success', 'Selected element has been removed successfully');
        return 1;
    }

    /**
     * Persists a category
     * 
     * @return string
     */
    public function saveAction()
    {
        $input = $this->request->getPost();

        $service = $this->getModuleService('categoryService');

        if (!empty($input['category']['id'])) {
            if ($service->update($this->request->getAll())) {
                $this->flashBag->set('success', 'The element has been updated successfully');
                return '1';
            }

        } else {
            if ($service->add($this->request->getAll())) {
                $this->flashBag->set('success', 'The element has been created successfully');
                return $service->getLastId();
            }
        }
    }
}
