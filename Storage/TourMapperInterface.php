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
     * Fetches tour data by its associated id
     * 
     * @param string $id Tour id
     * @param boolean $withTranslations Whether to fetch translations or not
     * @return array
     */
    public function fetchById($id, $withTranslations);
}
