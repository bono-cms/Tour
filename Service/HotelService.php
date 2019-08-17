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
use Krystal\Image\Tool\ImageManagerInterface;
use Cms\Service\AbstractManager;
use Cms\Service\WebPageManagerInterface;
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
     * Web page service
     * 
     * @var \Cms\Service\WebPageManagerInterface
     */
    private $webPageManager;

    /**
     * Image manager service
     * 
     * @var \Krystal\Image\Tool\ImageManagerInterface
     */
    private $imageManager;

    /**
     * State initialization
     * 
     * @param \Tour\Storage\HotelMapperInterface $hotelMapper
     * @param \Cms\Service\WebPageManagerInterface $webPageManager
     * @param \Krystal\Image\Tool\ImageManagerInterface $imageManager
     * @return void
     */
    public function __construct(HotelMapperInterface $hotelMapper, WebPageManagerInterface $webPageManager, ImageManagerInterface $imageManager)
    {
        $this->hotelMapper = $hotelMapper;
        $this->webPageManager = $webPageManager;
        $this->imageManager = $imageManager;
    }

    /**
     * Returns a collection of switching URLs
     * 
     * @param string $id Hotel ID
     * @return array
     */
    public function getSwitchUrls($id)
    {
        return $this->hotelMapper->createSwitchUrls($id, 'Tour (Hotels)', 'Tour:Tour@hotelAction');
    }

    /**
     * {@inheritDoc}
     */
    protected function toEntity(array $row)
    {
        $entity = new HotelEntity();
        $entity->setId($row['id'])
               ->setLangId($row['lang_id'])
               ->setWebPageId($row['web_page_id'])
               ->setOrder($row['order'])
               ->setCover($row['cover'])
               ->setName($row['name'])
               ->setDescription($row['description'])
               ->setPhone($row['phone'])
               ->setAddress($row['address'])
               ->setDistances($row['distances'])
               ->setRooms($row['rooms'])
               ->setSlug($row['slug'])
               ->setUrl($this->webPageManager->surround($entity->getSlug(), $entity->getLangId()));

        // Configure image bag
        $imageBag = clone $this->imageManager->getImageBag();
        $imageBag->setId((int) $row['id'])
                 ->setCover($row['cover']);

        $entity->setImageBag($imageBag);

        return $entity;
    }

    /**
     * Find attached hotels by tour id
     * 
     * @param int $id Tour id
     * @return array
     */
    public function findHotelsByTourId($id)
    {
        // Get raw rows and convert them to entities
        $rows = ArrayUtils::filterArray($this->hotelMapper->findHotelsByTourId($id), function($row){
            $entity = new HotelEntity();
            $entity->setId($row['id'])
                   ->setLangId($row['lang_id'])
                   ->setName($row['name'])
                   ->setSlug($row['slug'])
                   ->setUrl($this->webPageManager->surround($entity->getSlug(), $entity->getLangId()));

            // Configure image bag
            $imageBag = clone $this->imageManager->getImageBag();
            $imageBag->setId((int) $row['id'])
                     ->setCover($row['cover']);

            $entity->setImageBag($imageBag);

            return $entity;
        });

        return $rows;
    }

    /**
     * Fetch hotels as a hash collection
     * 
     * @return array
     */
    public function fetchList()
    {
        return ArrayUtils::arrayList($this->hotelMapper->fetchAll(false), 'id', 'name');
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
     * Saves a page
     * 
     * @param array $input
     * @param boolean Whether to create a new record
     * @return boolean
     */
    private function savePage(array $input, $create)
    {
        // Request variables
        $hotel =& $input['data']['hotel'];
        $file = isset($input['files']['file']) ? $input['files']['file'] : false;

        $hotel = ArrayUtils::arrayWithout($hotel, array('slug'));

        // Adding
        if (!$hotel['id'] && $file) {
            // Define image attribute
            $hotel['cover'] = $file->getUniqueName();
        }

        // If file new provided, than start handling
        if ($hotel['id'] && $file) {
            // If we have a previous cover, then we gotta remove it
            $this->imageManager->delete($hotel['id'], $hotel['cover']);
            $this->imageManager->upload($hotel['id'], $file);

            // Now override cover's value with file's base name we currently have from user's input
            $hotel['cover'] = $file->getUniqueName();
        }

        // Save page
        $this->hotelMapper->savePage('Tour (Hotels)', 'Tour:Tour@hotelAction', $hotel, $input['data']['translation']);

        // Get last id
        if ($create === true) {
            $id = $this->getLastId();
        } else {
            $id = $hotel['id'];
        }

        if (!$hotel['id'] && $file) {
            // And now upload image
            $this->imageManager->upload($id, $file);
        }

        return $id;
    }

    /**
     * Adds a hotel
     * 
     * @param array $input Raw input data
     * @return int Hotel id
     */
    public function add(array $input)
    {
        return $this->savePage($input, true);
    }

    /**
     * Updates a hotel
     * 
     * @param array $input Raw input data
     * @return int Hotel id
     */
    public function update(array $input)
    {
        return $this->savePage($input, false);
    }
}
