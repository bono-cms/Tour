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
use Krystal\Image\Tool\ImageManagerInterface;

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
     * Image manager service
     * 
     * @var \Krystal\Image\Tool\ImageManagerInterface
     */
    private $imageManager;

    /**
     * State initialization
     * 
     * @param \Tour\Storage\TourMapperInterface $tourMapper
     * @param \Cms\Service\WebPageManagerInterface $webPageManager
     * @param \Krystal\Image\Tool\ImageManagerInterface $imageManager
     * @return void
     */
    public function __construct(TourMapperInterface $tourMapper, WebPageManagerInterface $webPageManager, ImageManagerInterface $imageManager)
    {
        $this->tourMapper = $tourMapper;
        $this->webPageManager = $webPageManager;
        $this->imageManager = $imageManager;
    }

    /**
     * Increments view count by tour id
     * 
     * @param string $id Tour ID
     * @return boolean
     */
    public function incrementViewCount($id)
    {
        return $this->tourMapper->incrementViewCount($id);
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
               ->setDestinationId($tour['destination_id'])
               ->setLangConstraintId($tour['lang_constraint_id'])
               ->setWebPageId($tour['web_page_id'], TourEntity::FILTER_INT)
               ->setLangId($tour['lang_id'], TourEntity::FILTER_INT)
               ->setOrder($tour['order'], TourEntity::FILTER_INT)
               ->setAdults($tour['adults'], TourEntity::FILTER_INT)
               ->setChildren($tour['children'], TourEntity::FILTER_INT)
               ->setRecommended($tour['recommended'], TourEntity::FILTER_BOOL)
               ->setPrice($tour['price'], TourEntity::FILTER_FLOAT)
               ->setStartPrice($tour['start_price'], TourEntity::FILTER_FLOAT)
               ->setCover($tour['cover'], TourEntity::FILTER_SAFE_TAGS)
               ->setShort($tour['short'], TourEntity::FILTER_SAFE_TAGS)
               ->setDescription($tour['description'], TourEntity::FILTER_SAFE_TAGS)
               ->setIncluded($tour['included'], TourEntity::FILTER_SAFE_TAGS)
               ->setExcluded($tour['excluded'], TourEntity::FILTER_SAFE_TAGS)
               ->setName($tour['name'], TourEntity::FILTER_HTML)
               ->setSlug($tour['slug'], TourEntity::FILTER_HTML)
               ->setChangeFreq($tour['changefreq'])
               ->setPriority($tour['priority'])
               ->setUrl($this->webPageManager->surround($entity->getSlug(), $entity->getLangId()))
               ->setTitle($tour['title'], TourEntity::FILTER_HTML)
               ->setSeo(isset($tour['seo']) ? $tour['seo'] : null, TourEntity::FILTER_BOOL)
               ->setPublished(isset($tour['published']) ? $tour['published'] : null, TourEntity::FILTER_BOOL)
               ->setKeywords($tour['meta_keywords'], TourEntity::FILTER_HTML)
               ->setMetaDescription($tour['meta_description'], TourEntity::FILTER_HTML)
               ->setViews($tour['views'], TourEntity::FILTER_INT);

        // If it's not new tour, then it must have attached categories
        if ($entity->getId()) {
            $entity->setCategoryIds($this->tourMapper->findCategoryIds($entity->getId()));
            $entity->setRelatedIds($this->tourMapper->findRelatedIds($entity->getId()));
            $entity->setHotelIds($this->tourMapper->findHotelIds($entity->getId()));
        }

        if (isset($tour['category'])) {
            $entity->setCategory($tour['category'], TourEntity::FILTER_SAFE_TAGS);
        }

        if (isset($tour['categories'])) {
            $entity->setCategories($tour['categories']);
        }
        
        // Configure image bag
        $imageBag = clone $this->imageManager->getImageBag();
        $imageBag->setId((int) $tour['id'])
                 ->setCover($tour['cover']);

        $entity->setImageBag($imageBag);

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
     * Fetches basic information about tours and their categories
     * 
     * @param mixed $excludedId Excluded category id
     * @return array
     */
    public function fetchBasic($excludedId = null)
    {
        // Grab raw rows
        $rows = $this->tourMapper->fetchBasic($excludedId);

        $output = array();

        // Turn rows into entities
        foreach ($rows as $row) {
            $entity = new VirtualEntity();
            $entity->setCategory($row['category'])
                   ->setTour($row['tour'])
                   ->setSlug($row['slug'])
                   ->setLangId($row['lang_id'])
                   ->setUrl($this->webPageManager->surround($entity->getSlug(), $entity->getLangId()));
            
            $output[] = $entity;
        }

        return ArrayUtils::categorize($output, 'category');
    }

    /**
     * Fetch recommended tour rows
     * 
     * @return array
     */
    public function fetchRecommended()
    {
        $filter = array(
            'recommended' => '1'
        );

        return $this->filter($filter, null, null, false, true);
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
     * @param array $excludedIds A collection excluded tour IDs
     * @return array
     */
    public function fetchList(array $excludedIds = array())
    {
        $rows = ArrayUtils::arrayList($this->tourMapper->fetchList(), 'id', 'name');

        // Process excluded IDs if required
        if (!empty($excludedIds)) {
            foreach ($excludedIds as $excludedId) {
                if (isset($rows[$excludedId])) {
                    unset($rows[$excludedId]);
                }
            }
        }

        return $rows;
    }

    /**
     * Returns category's last id
     * 
     * @return string
     */
    public function getLastId()
    {
        return $this->tourMapper->getMaxId();
    }

    /**
     * Deletes a category by its ID
     * 
     * @param int|array $id Tour ID (or IDs)
     * @return boolean
     */
    public function delete($id)
    {
        return $this->tourMapper->deletePage($id) && $this->imageManager->delete($id);
    }

    /**
     * Saves a page
     * 
     * @param array $input
     * @param boolean Whether to create a new record
     * @return boolean
     */
    private function savePage(array $input, $create)
    {
        // Request variables
        $tour =& $input['data']['tour'];
        $file = isset($input['files']['file']) ? $input['files']['file'] : false;

        $tour = ArrayUtils::arrayWithout($tour, array('slug'));

        // Adding
        if (!$tour['id'] && $file) {
            // Define image attribute
            $tour['cover'] = $file->getUniqueName();
        }

        // If file new provided, than start handling
        if ($tour['id'] && $file) {
            // If we have a previous cover, then we gotta remove it
            $this->imageManager->delete($tour['id'], $tour['cover']);
            $this->imageManager->upload($tour['id'], $file);

            // Now override cover's value with file's base name we currently have from user's input
            $tour['cover'] = $file->getUniqueName();
        }

        // Save page
        $this->tourMapper->savePage('Tour (Tours)', 'Tour:Tour@tourAction', $tour, $input['data']['translation']);

        // Get last id
        if ($create === true) {
            $id = $this->getLastId();
        } else {
            $id = $tour['id'];
        }

        if (!$tour['id'] && $file) {
            // And now upload image
            $this->imageManager->upload($id, $file);
        }

        // Attach related ones
        $this->tourMapper->attachCategories($id, isset($input['data']['categories']) ? $input['data']['categories'] : array());
        $this->tourMapper->attachRelatedTours($id, isset($input['data']['related']) ? $input['data']['related'] : array());
        $this->tourMapper->attachHotels($id, isset($input['data']['hotels']) ? $input['data']['hotels'] : array());

        return $id;
    }

    /**
     * Adds a tour
     * 
     * @param array $input Raw input data
     * @return int Tour id
     */
    public function add(array $input)
    {
        return $this->savePage($input, true);
    }

    /**
     * Updates a tour
     * 
     * @param array $input Raw input data
     * @return int Tour id
     */
    public function update(array $input)
    {
        return $this->savePage($input, false);
    }
}
