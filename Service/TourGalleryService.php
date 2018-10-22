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
use Tour\Storage\TourGalleryMapperInterface;

final class TourGalleryService extends AbstractManager
{
    /**
     * Any tour gallery mapper
     * 
     * @var \Tour\Storage\TourGalleryMapperInterface
     */
    private $tourGalleryMapper;

    /**
     * State initialization
     * 
     * @param \Tour\Storage\TourGalleryMapperInterface $tourGalleryMapper
     * @return void
     */
    public function __construct(TourGalleryMapperInterface $tourGalleryMapper)
    {
        $this->tourGalleryMapper = $tourGalleryMapper;
    }

    /**
     * {@inheritDoc}
     */
    protected function toEntity(array $row)
    {
        $image = new VirtualEntity();
        $image->setId($row['id'])
              ->setTourId($row['tour_id'])
              ->setOrder($row['order'])
              ->setImage($row['image']);

        return $image;
    }

    /**
     * Returns last image ID
     * 
     * @return int
     */
    public function getLastId()
    {
        return $this->tourGalleryMapper->getMaxId();
    }

    /**
     * Fetch all images
     * 
     * @param int $tourId Attached tour ID
     * @param boolean $sort Whether to sort by corresponding sorting order
     * @return array
     */
    public function fetchAll($tourId, $sort)
    {
        return $this->prepareResults($this->tourGalleryMapper->fetchAll($tourId, $sort));
    }

    /**
     * Fetch image by its ID
     * 
     * @param int $id Image ID
     * @return mixed
     */
    public function fetchById($id)
    {
        return $this->prepareResult($this->tourGalleryMapper->findByPk($id));
    }

    /**
     * Deletes an image by its ID
     * 
     * @param int $id Image ID
     * @return boolean
     */
    public function deleteById($id)
    {
        return $this->tourGalleryMapper->deleteByPk($id);
    }

    /**
     * Adds an image
     * 
     * @param array $input
     * @return boolean
     */
    public function add(array $input)
    {
        return $this->tourGalleryMapper->persist($input);
    }

    /**
     * Updates an image
     * 
     * @param array $input
     * @return boolean
     */
    public function update(array $input)
    {
        return $this->tourGalleryMapper->persist($input);
    }
}
