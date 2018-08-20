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
use Krystal\Stdlib\VirtualEntity;
use Krystal\Stdlib\ArrayUtils;

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
     * State initialization
     * 
     * @param \Tour\Storage\CategoryMapperInterface $categoryMapper
     * @param \Cms\Service\WebPageManagerInterface $webPageManager
     * @return void
     */
    public function __construct(CategoryMapperInterface $categoryMapper, WebPageManagerInterface $webPageManager)
    {
        $this->categoryMapper = $categoryMapper;
        $this->webPageManager = $webPageManager;
    }

    /**
     * {@inheritDoc}
     */
    protected function toEntity(array $category)
    {
        $entity = new VirtualEntity();
        $entity->setId($category['id'], VirtualEntity::FILTER_INT)
               ->setWebPageId($category['web_page_id'], VirtualEntity::FILTER_INT)
               ->setLangId($category['lang_id'], VirtualEntity::FILTER_INT)
               ->setDescription($category['description'], VirtualEntity::FILTER_SAFE_TAGS)
               ->setName($category['name'], VirtualEntity::FILTER_HTML)
               ->setSlug($category['slug'], VirtualEntity::FILTER_HTML)
               ->setUrl($this->webPageManager->surround($entity->getSlug(), $entity->getLangId()))
               ->setTitle($category['title'], VirtualEntity::FILTER_HTML)
               ->setSeo(isset($category['seo']) ? $category['seo'] : null, VirtualEntity::FILTER_BOOL)
               ->setKeywords($category['meta_keywords'], VirtualEntity::FILTER_HTML)
               ->setMetaDescription($category['meta_description'], VirtualEntity::FILTER_HTML);

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
     * Returns category's last id
     * 
     * @return string
     */
    public function getLastId()
    {
        return $this->categoryMapper->getLastId();
    }

    /**
     * Saves a page
     * 
     * @param array $input
     * @return boolean
     */
    private function savePage(array $input)
    {
        $input['category'] = ArrayUtils::arrayWithout($input['category'], array('slug'));
        return $this->categoryMapper->savePage('Tour (Categories)', 'Tour:Tour@indexAction', $input['category'], $input['translation']);
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
