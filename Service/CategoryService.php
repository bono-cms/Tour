<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Tour\Service;

use Tour\Storage\CategoryMapperInterface;
use Cms\Service\AbstractManager;
use Cms\Service\WebPageManagerInterface;
use Krystal\Stdlib\ArrayUtils;
use Krystal\Image\Tool\ImageManagerInterface;

final class CategoryService extends AbstractManager
{
    /**
     * Any compliant mapper
     * 
     * @var \Tour\Storage\CategoryMapperInterface
     */
    private $categoryMapper;

    /**
     * Web page service
     * 
     * @var \Cms\Service\WebPageManagerInterface
     */
    private $webPageManager;

    /**
     * Image manager service
     * 
     * @var \Krystal\Image\Tool\ImageManagerInterface
     */
    private $imageManager;

    /**
     * State initialization
     * 
     * @param \Tour\Storage\CategoryMapperInterface $categoryMapper
     * @param \Cms\Service\WebPageManagerInterface $webPageManager
     * @param \Krystal\Image\Tool\ImageManagerInterface $imageManager
     * @return void
     */
    public function __construct(CategoryMapperInterface $categoryMapper, WebPageManagerInterface $webPageManager, ImageManagerInterface $imageManager)
    {
        $this->categoryMapper = $categoryMapper;
        $this->webPageManager = $webPageManager;
        $this->imageManager = $imageManager;
    }

    /**
     * Returns a collection of switching URLs
     * 
     * @param string $id Category ID
     * @return array
     */
    public function getSwitchUrls($id)
    {
        return $this->categoryMapper->createSwitchUrls($id, 'Tour (Categories)', 'Tour:Tour@categoryAction');
    }

    /**
     * {@inheritDoc}
     */
    protected function toEntity(array $category)
    {
        $entity = new CategoryEntity();
        $entity->setId($category['id'], CategoryEntity::FILTER_INT)
               ->setWebPageId($category['web_page_id'], CategoryEntity::FILTER_INT)
               ->setLangId($category['lang_id'], CategoryEntity::FILTER_INT)
               ->setOrder($category['order'], CategoryEntity::FILTER_INT)
               ->setCover($category['cover'], CategoryEntity::FILTER_SAFE_TAGS)
               ->setDescription($category['description'], CategoryEntity::FILTER_SAFE_TAGS)
               ->setName($category['name'], CategoryEntity::FILTER_HTML)
               ->setSlug($category['slug'], CategoryEntity::FILTER_HTML)
               ->setUrl($this->webPageManager->surround($entity->getSlug(), $entity->getLangId()))
               ->setTitle($category['title'], CategoryEntity::FILTER_HTML)
               ->setSeo(isset($category['seo']) ? $category['seo'] : null, CategoryEntity::FILTER_BOOL)
               ->setKeywords($category['meta_keywords'], CategoryEntity::FILTER_HTML)
               ->setMetaDescription($category['meta_description'], CategoryEntity::FILTER_HTML);

        // Configure image bag
        $imageBag = clone $this->imageManager->getImageBag();
        $imageBag->setId($entity->getId())
                 ->setCover($entity->getCover());

        $entity->setImageBag($imageBag);

        return $entity;
    }

    /**
     * Fetches category data by its associated id
     * 
     * @param string $id Category id
     * @param boolean $withTranslations Whether to fetch translations or not
     * @return array
     */
    public function fetchById($id, $withTranslations)
    {
        if ($withTranslations == true) {
            return $this->prepareResults($this->categoryMapper->fetchById($id, true));
        } else {
            return $this->prepareResult($this->categoryMapper->fetchById($id, false));
        }
    }

    /**
     * Fetches as a list
     * 
     * @param boolean $list Whether to fetch as a list or not
     * @return array
     */
    public function fetchList($list)
    {
        $rows = $this->categoryMapper->fetchAll();

        if ($list === true) {
            return ArrayUtils::arrayList($rows, 'id', 'name');
        }

        return $this->prepareResults($rows);
    }

    /**
     * Returns category's last id
     * 
     * @return string
     */
    public function getLastId()
    {
        return $this->categoryMapper->getLastId();
    }

    /**
     * Deletes a category by its ID
     * 
     * @param int $id Category ID
     * @return boolean
     */
    public function deleteById($id)
    {
        return $this->categoryMapper->deletePage($id) && $this->imageManager->delete($id);
    }

    /**
     * Saves a page
     * 
     * @param array $input
     * @return boolean
     */
    private function savePage(array $input)
    {
        // Request variables
        $file = $input['files']['file'];
        $category =& $input['data']['category'];

        // Adding
        if (!$category['id']) {
            $this->filterFileInput($file);

            // Define image attribute
            $category['cover'] = $file[0]->getName();
        }

        // If file new provided, than start handling
        if ($category['id'] && !empty($input['files'])) {
            // If we have a previous cover, then we gotta remove it
            $this->imageManager->delete($category['id'], $category['cover']);

            // Before we start uploading a file, we need to filter its base name
            $this->filterFileInput($file);
            $this->imageManager->upload($category['id'], $file);

            // Now override cover's value with file's base name we currently have from user's input
            $category['cover'] = $file[0]->getName();
        }

        $category = ArrayUtils::arrayWithout($category, array('slug'));
        $this->categoryMapper->savePage('Tour (Categories)', 'Tour:Tour@categoryAction', $category, $input['data']['translation']);

        // Grab ID
        $id = $category['id'] ? $category['id'] : $this->getLastId();

        if (!$category['id']) {
            // And now upload image
            $this->imageManager->upload($id, $file);
        }

        return true;
    }

    /**
     * Adds a category
     * 
     * @param array $input Raw input data
     * @return boolean Depending on success
     */
    public function add(array $input)
    {
        return $this->savePage($input);
    }

    /**
     * Updates a category
     * 
     * @param array $input Raw input data
     * @return boolean Depending on success
     */
    public function update(array $input)
    {
        return $this->savePage($input);
    }
}
