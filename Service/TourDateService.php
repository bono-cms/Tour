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
use Tour\Storage\TourDateMapperInterface;

final class TourDateService extends AbstractManager
{
    /**
     * Tour date mapper
     * 
     * @var \Tour\Storage\TourDateMapperInterface
     */
    private $tourDateMapper;

    /**
     * State initialization
     * 
     * @param \Tour\Storage\TourDateMapperInterface $tourDateMapper
     * @return void
     */
    public function __construct(TourDateMapperInterface $tourDateMapper)
    {
        $this->tourDateMapper = $tourDateMapper;
    }

    /**
     * {@inheritDoc}
     */
    protected function toEntity($row)
    {
        $entity = new VirtualEntity();
        $entity->setStart($row['start'])
               ->setEnd($row['end'])
               ->setId($row['id'])
               ->setTourId($row['tour_id']);

        return $entity;
    }

    /**
     * Delete tour date by its ID
     * 
     * @param int $id Tour date ID
     * @return boolean
     */
    public function deleteById($id)
    {
        return $this->tourDateMapper->deleteByPk($id);
    }

    /**
     * Returns last ID
     * 
     * @return int
     */
    public function getLastId()
    {
        return $this->tourDateMapper->getMaxId();
    }

    /**
     * Fetch tour date by its ID
     * 
     * @param int $id Tour date ID
     * @return array
     */
    public function fetchById($id)
    {
        return $this->prepareResult($this->tourDateMapper->findByPk($id));
    }

    /**
     * Fetch dates by tour ID
     * 
     * @param int $tourId
     * @return array
     */
    public function fetchByTourId($tourId)
    {
        return $this->prepareResults($this->tourDateMapper->fetchByTourId($tourId));
    }

    /**
     * Saves tour date
     * 
     * @param array $input
     * @return boolean
     */
    public function save(array $input)
    {
        return $this->tourDateMapper->persist($input);
    }
}
