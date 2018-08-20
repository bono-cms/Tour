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
use Krystal\Stdlib\VirtualEntity;

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
    protected function toEntity()
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
}
