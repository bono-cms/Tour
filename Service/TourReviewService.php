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
     * @param int|null $page Current page number
     * @param int|null $itemsPerPage Per page count
     * @return array
     */
    public function fetchAll($tourId = null, $page = null, $itemsPerPage = null)
    {
        return $this->tourReviewMapper->fetchAll($tourId, $page, $itemsPerPage);
    }
}
