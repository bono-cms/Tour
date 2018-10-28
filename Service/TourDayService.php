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
use Cms\Service\AbstractManager;
use Tour\Storage\TourDayMapperInterface;

final class TourDayService extends AbstractManager
{
    /**
     * Tour day mapper
     * 
     * @var \Tour\Storage\TourDayMapperInterface
     */
    private $tourDayMapper;

    /**
     * State initialization
     * 
     * @param \Tour\Storage\TourDayMapperInterface $tourDayMapper
     * @return void
     */
    public function __construct(TourDayMapperInterface $tourDayMapper)
    {
        $this->tourDayMapper = $tourDayMapper;
    }

    /**
     * {@inheritDoc}
     */
    protected function toEntity(array $row)
    {
        $day = new VirtualEntity();
        $day->setId($row['id'])
            ->setLangId($row['lang_id'])
            ->setTourId($row['tour_id'])
            ->setOrder($row['order'])
            ->setTime($row['time'])
            ->setTitle($row['title'])
            ->setDescription($row['description']);

        return $day;
    }

    /**
     * Deletes tour day by its ID
     * 
     * @param int $id Tour day ID
     * @return boolean
     */
    public function deleteById($id)
    {
        return $this->tourDayMapper->deleteByPk($id);
    }

    /**
     * Returns last id
     * 
     * @return int
     */
    public function getLastId()
    {
        return $this->tourDayMapper->getMaxId();
    }

    /**
     * Fetch all days
     * 
     * @param int $tourId Attached tour ID
     * @param boolean $sort Whether to sort by corresponding sorting order
     * @return array
     */
    public function fetchAll($tourId, $sort)
    {
        return $this->prepareResults($this->tourDayMapper->fetchAll($tourId, $sort));
    }

    /**
     * Fetches tour day by its associated id
     * 
     * @param string $id
     * @param boolean $withTranslations Whether to fetch translations or not
     * @return array
     */
    public function fetchById($id, $withTranslations)
    {
        if ($withTranslations == true) {
            return $this->prepareResults($this->tourDayMapper->fetchById($id, true));
        } else {
            return $this->prepareResult($this->tourDayMapper->fetchById($id, false));
        }
    }

    /**
     * Saves an entity
     * 
     * @param array $input
     * @return boolean
     */
    public function save(array $input)
    {
        return $this->tourDayMapper->saveEntity($input['day'], $input['translation']);
    }
}
