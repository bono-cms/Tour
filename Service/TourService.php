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

use Cms\Service\WebPageManagerInterface;
use Cms\Service\AbstractManager;
use Tour\Storage\TourMapperInterface;
use Krystal\Stdlib\ArrayUtils;
use Krystal\Stdlib\VirtualEntity;
use Krystal\Db\Filter\FilterableServiceInterface;

final class TourService extends AbstractManager implements FilterableServiceInterface
{
    /**
     * Tour mapper
     * 
     * @var \Tour\Storage\TourMapperInterface
     */
    private $tourMapper;

    /**
     * Web page service
     * 
     * @var \Cms\Service\WebPageManagerInterface
     */
    private $webPageManager;

    /**
     * State initialization
     * 
     * @param \Tour\Storage\TourMapperInterface $tourMapper
     * @param \Cms\Service\WebPageManagerInterface $webPageManager
     * @return void
     */
    public function __construct(TourMapperInterface $tourMapper, WebPageManagerInterface $webPageManager)
    {
        $this->tourMapper = $tourMapper;
        $this->webPageManager = $webPageManager;
    }

    /**
     * {@inheritDoc}
     */
    protected function toEntity(array $tour)
    {
        $entity = new VirtualEntity();
        $entity->setId($tour['id'], VirtualEntity::FILTER_INT)
               ->setWebPageId($tour['web_page_id'], VirtualEntity::FILTER_INT)
               ->setLangId($tour['lang_id'], VirtualEntity::FILTER_INT)
               ->setOrder($tour['order'], VirtualEntity::FILTER_INT)
               ->setDescription($tour['description'], VirtualEntity::FILTER_SAFE_TAGS)
               ->setName($tour['name'], VirtualEntity::FILTER_HTML)
               ->setSlug($tour['slug'], VirtualEntity::FILTER_HTML)
               ->setUrl($this->webPageManager->surround($entity->getSlug(), $entity->getLangId()))
               ->setTitle($tour['title'], VirtualEntity::FILTER_HTML)
               ->setSeo(isset($tour['seo']) ? $tour['seo'] : null, VirtualEntity::FILTER_BOOL)
               ->setKeywords($tour['meta_keywords'], VirtualEntity::FILTER_HTML)
               ->setMetaDescription($tour['meta_description'], VirtualEntity::FILTER_HTML);

        return $entity;
    }

    /**
     * Returns paginator instance
     * 
     * @return \Krystal\Paginate\Paginator
     */
    public function getPaginator()
    {
        return $this->tourMapper->getPaginator();
    }

    /**
     * Filters the raw input
     * 
     * @param array|\ArrayAccess $input Raw input data
     * @param integer $page Current page number
     * @param integer $itemsPerPage Items per page to be displayed
     * @param string $sortingColumn Column name to be sorted
     * @param string $desc Whether to sort in DESC order
     * @param array $parameters
     * @return array
     */
    public function filter($input, $page, $itemsPerPage, $sortingColumn, $desc, array $parameters = array())
    {
        return $this->prepareResults($this->tourMapper->filter($input, $page, $itemsPerPage, $sortingColumn, $desc, $parameters), false);
    }

    /**
     * Fetches tour data by its associated id
     * 
     * @param string $id Tour id
     * @param boolean $withTranslations Whether to fetch translations or not
     * @return array
     */
    public function fetchById($id, $withTranslations)
    {
        if ($withTranslations == true) {
            return $this->prepareResults($this->tourMapper->fetchById($id, true));
        } else {
            return $this->prepareResult($this->tourMapper->fetchById($id, false));
        }
    }
    
    /**
     * Returns category's last id
     * 
     * @return string
     */
    public function getLastId()
    {
        return $this->tourMapper->getLastId();
    }

    /**
     * Deletes a category by its ID
     * 
     * @param int $id Category ID
     * @return boolean
     */
    public function deleteById($id)
    {
        return $this->tourMapper->deletePage($id);
    }

    /**
     * Saves a page
     * 
     * @param array $input
     * @return boolean
     */
    private function savePage(array $input)
    {
        $input['tour'] = ArrayUtils::arrayWithout($input['tour'], array('slug'));
        return $this->tourMapper->savePage('Tour (Tours)', 'Tour:Tour@indexAction', $input['tour'], $input['translation']);
    }

    /**
     * Adds a tour
     * 
     * @param array $input Raw input data
     * @return boolean Depending on success
     */
    public function add(array $input)
    {
        return $this->savePage($input);
    }

    /**
     * Updates a tour
     * 
     * @param array $input Raw input data
     * @return boolean Depending on success
     */
    public function update(array $input)
    {
        return $this->savePage($input);
    }
}
