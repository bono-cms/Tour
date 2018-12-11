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

interface TourReviewMapperInterface
{
    /**
     * Counts non-published reviews
     * 
     * @return int
     */
    public function countUnpublished();

    /**
     * Marks review as published by its ID
     * 
     * @param int $id Review ID
     * @return boolean
     */
    public function approveById($id);

    /**
     * Fetch all tour reviews by its ID
     * 
     * @param int|null $tourId
     * @param boolean $published Whether fetch only published ones or not
     * @param int|null $page Current page number
     * @param int|null $itemsPerPage Per page count
     * @return array
     */
    public function fetchAll($tourId = null, $published, $page = null, $itemsPerPage = null);
}
