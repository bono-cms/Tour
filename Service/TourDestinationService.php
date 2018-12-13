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

use Krystal\Stdlib\ArrayUtils;
use Krystal\Stdlib\VirtualEntity;
use Cms\Service\AbstractManager;
use Tour\Storage\TourDestinationMapperInterface;

final class TourDestinationService extends AbstractManager implements TourDestinationMapperInterface
{
    /**
     * Any compliant tour destination mapper
     * 
     *  @var \Tour\Storage\TourDestinationMapperInterface
     */
    private $tourDestinationMapper;

    /**
     * State initialization
     * 
     * @param \Tour\Storage\TourDestinationMapperInterface $tourDestinationMapper
     * @return void
     */
    public function __construct(TourDestinationMapperInterface $tourDestinationMapper)
    {
        $this->tourDestinationMapper = $tourDestinationMapper;
    }

    /**
     * {@inheritDoc}
     */
    protected function toEntity(array $row)
    {
        $entity = new VirtualEntity();
        $entity->setId($row['id'], VirtualEntity::FILTER_INT)
               ->setLangId($row['lang_id'], VirtualEntity::FILTER_INT)
               ->setOrder($row['order'], VirtualEntity::FILTER_INT)
               ->setName($row['name'], VirtualEntity::FILTER_TAGS);

        return $entity;
    }

    /**
     * Saves tour destination
     * 
     * @param array $input
     * @return boolean
     */
    public function save(array $input)
    {
        return $this->tourDestinationMapper->saveEntity($input['destination'], $input['translation']);
    }

    /**
     * Deletes tour destination by its ID
     * 
     * @param int $id
     * @return boolean
     */
    public function deleteById($id)
    {
        return $this->tourDestinationMapper->deleteByPk($id);
    }

    /**
     * Returns last tour destination ID
     * 
     * @return int
     */
    public function getLastId()
    {
        return $this->tourDestinationMapper->getMaxId();
    }

    /**
     * Fetch destinations as a list
     * 
     * @return array
     */
    public function fetchList()
    {
        return ArrayUtils::arrayList($this->tourDestinationMapper->fetchAll(false), 'id', 'name');
    }

    /**
     * Fetch all tour destinations
     * 
     * @param boolean $sort Whether to sort by corresponding sorting order
     * @return array
     */
    public function fetchAll($sort)
    {
        return $this->prepareResults($this->tourDestinationMapper->fetchAll($sort));
    }

    /**
     * Fetch tour destination by its ID
     * 
     * @param int $id Tour destination ID
     * @param boolean $withTranslations Whether to fetch translations or not
     * @return mixed
     */
    public function fetchById($id, $withTranslations)
    {
        if ($withTranslations == true) {
            return $this->prepareResults($this->tourDestinationMapper->fetchById($id, $withTranslations));
        } else {
            return $this->prepareResult($this->tourDestinationMapper->fetchById($id, $withTranslations));
        }
    }
}
