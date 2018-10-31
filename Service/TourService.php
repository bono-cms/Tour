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
     * Returns a collection of switching URLs
     * 
     * @param string $id Tour ID
     * @return array
     */
    public function getSwitchUrls($id)
    {
        return $this->tourMapper->createSwitchUrls($id, 'Tour (Tours)', 'Tour:Tour@tourAction');
    }

    /**
     * {@inheritDoc}
     */
    protected function toEntity(array $tour)
    {
        $entity = new TourEntity();
        $entity->setId($tour['id'], TourEntity::FILTER_INT)
               ->setWebPageId($tour['web_page_id'], TourEntity::FILTER_INT)
               ->setLangId($tour['lang_id'], TourEntity::FILTER_INT)
               ->setOrder($tour['order'], TourEntity::FILTER_INT)
               ->setAdults($tour['adults'], TourEntity::FILTER_INT)
               ->setChildren($tour['children'], TourEntity::FILTER_INT)
               ->setRecommended($tour['recommended'], TourEntity::FILTER_BOOL)
               ->setPrice($tour['price'], TourEntity::FILTER_FLOAT)
               ->setStartPrice($tour['start_price'], TourEntity::FILTER_FLOAT)
               ->setCover($tour['cover'], TourEntity::FILTER_SAFE_TAGS)
               ->setDescription($tour['description'], TourEntity::FILTER_SAFE_TAGS)
               ->setIncluded($tour['included'], TourEntity::FILTER_SAFE_TAGS)
               ->setExcluded($tour['excluded'], TourEntity::FILTER_SAFE_TAGS)
               ->setName($tour['name'], TourEntity::FILTER_HTML)
               ->setSlug($tour['slug'], TourEntity::FILTER_HTML)
               ->setUrl($this->webPageManager->surround($entity->getSlug(), $entity->getLangId()))
               ->setTitle($tour['title'], TourEntity::FILTER_HTML)
               ->setSeo(isset($tour['seo']) ? $tour['seo'] : null, TourEntity::FILTER_BOOL)
               ->setPublished(isset($tour['published']) ? $tour['published'] : null, TourEntity::FILTER_BOOL)
               ->setKeywords($tour['meta_keywords'], TourEntity::FILTER_HTML)
               ->setMetaDescription($tour['meta_description'], TourEntity::FILTER_HTML);

        // If it's not new tour, then it must have attached categories
        if ($entity->getId()) {
            $entity->setCategoryIds($this->tourMapper->findCategoryIds($entity->getId()));
            $entity->setRelatedIds($this->tourMapper->findRelatedIds($entity->getId()));
        }

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
     * Fetch all tours filtered by category ID
     * 
     * @param int $categoryId
     * @param int $page Current page number
     * @param int $itemsPerPage Per page count
     * @return array
     */
    public function fetchAllByCategoryId($categoryId, $page, $itemsPerPage)
    {
        $filter = array(
            'category_id' => $categoryId
        );

        return $this->filter($filter, $page, $itemsPerPage, false, true);
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
     * Fetch all tours as a hash map
     * 
     * @return array
     */
    public function fetchList()
    {
        return ArrayUtils::arrayList($this->tourMapper->fetchList(), 'id', 'name');
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
     * @param int|array $id Tour ID (or IDs)
     * @return boolean
     */
    public function delete($id)
    {
        return $this->tourMapper->deletePage($id);
    }

    /**
     * Saves a page
     * 
     * @param array $input
     * @param int $id Tour ID
     * @return boolean
     */
    private function savePage(array $input, $id)
    {
        $input['tour'] = ArrayUtils::arrayWithout($input['tour'], array('slug'));

        $this->tourMapper->savePage('Tour (Tours)', 'Tour:Tour@tourAction', $input['tour'], $input['translation']);

        // Attach related ones
        $this->tourMapper->attachCategories($id, isset($input['categories']) ? $input['categories'] : array());
        $this->tourMapper->attachRelatedTours($id, isset($input['related']) ? $input['related'] : array());

        return true;
    }

    /**
     * Adds a tour
     * 
     * @param array $input Raw input data
     * @return boolean Depending on success
     */
    public function add(array $input)
    {
        $id = $this->getLastId();
        $this->savePage($input, $id);

        return $id;
    }

    /**
     * Updates a tour
     * 
     * @param array $input Raw input data
     * @return boolean Depending on success
     */
    public function update(array $input)
    {
        return $this->savePage($input, $input['tour']['id']);
    }
}
