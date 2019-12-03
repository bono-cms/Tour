<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Tour\Storage;

interface TourMapperInterface
{
    /**
     * Increments view count by tour id
     * 
     * @param string $id Tour ID
     * @return boolean
     */
    public function incrementViewCount($id);

    /**
     * Attach related tours
     * 
     * @param int $id Main tour ID
     * @param array $tourIds Related tour IDs to be attached
     * @return boolean
     */
    public function attachRelatedTours($id, array $tourIds);

    /**
     * Find related category IDs
     * 
     * @param int $id Tour ID
     * @return array
     */
    public function findRelatedIds($id);

    /**
     * Attach category IDs
     * 
     * @param int $id Tour ID
     * @param array $categoryIds Category IDs to be attached
     * @return boolean
     */
    public function attachCategories($id, array $categoryIds);

    /**
     * Find related category IDs
     * 
     * @param int $id Tour ID
     * @return array
     */
    public function findCategoryIds($id);

    /**
     * Attach hotel ids
     * 
     * @param int $id Tour id
     * @param array $hotelIds
     * @return boolean
     */
    public function attachHotels($id, array $hotelIds);

    /**
     * Find attached hotel ids
     * 
     * @param int $id Tour id
     * @return array
     */
    public function findHotelIds($id);

    /**
     * Fetches basic information about tours and their categories
     * 
     * @param mixed $excludedId Excluded category id
     * @return array
     */
    public function fetchBasic($excludedId = null);

    /**
     * Fetch many tours at once by their ids
     * 
     * @param array $ids Tour IDs
     * @return array
     */
    public function fetchByIds(array $ids);

    /**
     * Fetches tour data by its associated id
     * 
     * @param string $id Tour id
     * @param boolean $withTranslations Whether to fetch translations or not
     * @return array
     */
    public function fetchById($id, $withTranslations);
}
