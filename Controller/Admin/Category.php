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
     * @return string
     */
    private function createForm($entity)
    {
        // Append breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('Tours', 'Tour:Admin:Grid@indexAction')
                                       ->addOne(!is_array($entity) ? 'Add a category' : 'Edit the category');
        // Load plugins
        $this->view->getPluginBag()
                   ->load($this->getWysiwygPluginName());

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
        $category = new VirtualEntity();
        $category->setSeo(true); // Make SEO checked by default

        return $this->createForm($category);
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
            return $this->createForm($category);
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
            if ($service->update($input)) {
                $this->flashBag->set('success', 'The element has been updated successfully');
                return '1';
            }

        } else {
            if ($service->add($input)) {
                $this->flashBag->set('success', 'The element has been created successfully');
                return $service->getLastId();
            }
        }
    }
}