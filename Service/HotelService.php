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
use Krystal\Stdlib\ArrayUtils;
use Cms\Service\AbstractManager;
use Tour\Storage\HotelMapperInterface;

final class HotelService extends AbstractManager
{
    /**
     * Any compliant hotel mapper
     * 
     * @var \Tour\Storage\HotelMapperInterface
     */
    private $hotelMapper;

    /**
     * State initialization
     * 
     * @param \Tour\Storage\HotelMapperInterface $hotelMapper
     * @return void
     */
    public function __construct(HotelMapperInterface $hotelMapper)
    {
        $this->hotelMapper = $hotelMapper;
    }

    /**
     * {@inheritDoc}
     */
    protected function toEntity(array $row)
    {
        $entity = new VirtualEntity();
        $entity->setId($row['id'])
               ->setLangId($row['lang_id'])
               ->setOrder($row['order'])
               ->setName($row['name'])
               ->setDescription($row['description']);

        return $entity;
    }

    /**
     * Fetch all hotels
     * 
     * @param boolean $sort Whether to sort by corresponding sorting order
     * @return array
     */
    public function fetchAll($sort)
    {
        return $this->prepareResults($this->hotelMapper->fetchAll($sort));
    }

    /**
     * Fetches hotel by its associated id
     * 
     * @param string $id
     * @param boolean $withTranslations Whether to fetch translations or not
     * @return array
     */
    public function fetchById($id, $withTranslations)
    {
        if ($withTranslations == true) {
            return $this->prepareResults($this->hotelMapper->fetchById($id, true));
        } else {
            return $this->prepareResult($this->hotelMapper->fetchById($id, false));
        }
    }

    /**
     * Returns last hotel id
     * 
     * @return int
     */
    public function getLastId()
    {
        return $this->hotelMapper->getMaxId();
    }

    /**
     * Deletes a hotel by its id
     * 
     * @param int $id Hotel id
     * @return boolean
     */
    public function deleteById($id)
    {
        return $this->hotelMapper->deleteByPk($id);
    }

    /**
     * Save hotel data
     * 
     * @param array $input
     * @return boolean
     */
    public function save(array $input)
    {
        $input['hotel'] = ArrayUtils::arrayWithout($input['hotel'], array('slug'));

        // Save page
        return $this->hotelMapper->savePage('Tour (Hotels)', 'Tour:Tour@hotelAction', $input['hotel'], $input['translation']);
    }
}
