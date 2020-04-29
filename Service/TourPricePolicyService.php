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
use Tour\Storage\TourPricePolicyMapperInterface;

final class TourPricePolicyService extends AbstractManager
{
    /**
     * Tour price policy mapper
     * 
     * @var \Tour\Storage\TourPricePolicyMapperInterface
     */
    private $policyMapper;

    /**
     * State initialization
     * 
     * @param \Tour\Storage\TourPricePolicyMapperInterface $policyMapper
     * @return void
     */
    public function __construct(TourPricePolicyMapperInterface $policyMapper)
    {
        $this->policyMapper = $policyMapper;
    }

    /**
     * {@inheritDoc}
     */
    protected function toEntity(array $row)
    {
        $entity = new VirtualEntity();
        $entity->setId($row['id'])
               ->setTourId($row['tour_id'])
               ->setQty($row['qty'])
               ->setPrice($row['price']);

        return $entity;
    }

    /**
     * Returns last policy id
     * 
     * @return int
     */
    public function getLastId()
    {
        return $this->policyMapper->getMaxId();
    }

    /**
     * Fetch policy by its id
     * 
     * @param int $id Policy id
     * @return mixed
     */
    public function fetchById($id)
    {
        return $this->prepareResult($this->policyMapper->findByPk($id));
    }

    /**
     * Fetch all policies
     * 
     * @param int $tourId
     * @return array
     */
    public function fetchAll($tourId)
    {
        return $this->prepareResults($this->policyMapper->fetchAll($tourId));
    }

    /**
     * Delete a policy by its id
     * 
     * @param int $id Policy id
     * @return boolean
     */
    public function deleteById($id)
    {
        return $this->policyMapper->deleteByPk($id);
    }

    /**
     * Save a policy
     * 
     * @param array $input Raw input data
     * @return boolean
     */
    public function save(array $input)
    {
        return $this->policyMapper->persist($input);
    }
}
