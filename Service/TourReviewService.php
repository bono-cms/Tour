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

use Krystal\Stdlib\VirtualEntity;
use Krystal\Date\TimeHelper;
use Tour\Storage\TourReviewMapperInterface;
use Cms\Service\AbstractManager;

final class TourReviewService extends AbstractManager
{
    /**
     * Tour review mapper
     * 
     * @var \Tour\Storage\TourReviewMapperInterface
     */
    private $tourReviewMapper;

    /**
     * State initialization
     * 
     * @param \Tour\Storage\TourReviewMapperInterface $tourReviewMapper
     * @return void
     */
    public function __construct(TourReviewMapperInterface $tourReviewMapper)
    {
        $this->tourReviewMapper = $tourReviewMapper;
    }

    /**
     * {@inheritDoc}
     */
    protected function toEntity(array $row)
    {
        $entity = new VirtualEntity();
        $entity->setId($row['id'], VirtualEntity::FILTER_INT)
               ->setTourId($row['tour_id'], VirtualEntity::FILTER_INT)
               ->setTour($row['tour'], VirtualEntity::FILTER_TAGS)
               ->setDatetime($row['datetime'])
               ->setName($row['name'], VirtualEntity::FILTER_TAGS)
               ->setMessage($row['message'], VirtualEntity::FILTER_TAGS)
               ->setPublished($row['published'], VirtualEntity::FILTER_BOOL);

        return $entity;
    }

    /**
     * Counts non-published reviews
     * 
     * @return int
     */
    public function countUnpublished()
    {
        return $this->tourReviewMapper->countUnpublished();
    }

    /**
     * Marks review as published by its ID
     * 
     * @param int $id Review ID
     * @return boolean
     */
    public function approveById($id)
    {
        return $this->tourReviewMapper->approveById($id);
    }

    /**
     * Saves a review
     * 
     * @param array $input
     * @return boolean
     */
    public function save(array $input)
    {
        if (empty($input['id'])) {
            $input['datetime'] = TimeHelper::getNow();
            $input['published'] = 0; // By default disabled
        }

        return $this->tourReviewMapper->persist($input);
    }

    /**
     * Deletes tour review by its ID
     * 
     * @param int $id Review ID
     * @return boolean
     */
    public function deleteById($id)
    {
        return $this->tourReviewMapper->deleteByPk($id);
    }

    /**
     * Returns prepared pagination instance
     * 
     * @return \Krystal\Paginate\Paginator
     */
    public function getPaginator()
    {
        return $this->tourReviewMapper->getPaginator();
    }

    /**
     * Fetch all tour reviews by its ID
     * 
     * @param int $tourId
     * @param boolean $published Whether fetch only published ones or not
     * @param int|null $page Current page number
     * @param int|null $itemsPerPage Per page count
     * @return array
     */
    public function fetchAll($tourId = null, $published, $page = null, $itemsPerPage = null)
    {
        $rows = $this->tourReviewMapper->fetchAll($tourId, $published, $page, $itemsPerPage);
        return $this->prepareResults($rows);
    }
}
