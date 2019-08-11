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

use Tour\Storage\HotelGalleryMapperInterface;
use Krystal\Image\Tool\ImageManagerInterface;
use Cms\Service\AbstractManager;

final class HotelGalleryService extends AbstractManager
{
    /**
     * Any compliant hotel gallery mapper
     * 
     * @var \Tour\Storage\HotelGalleryMapperInterface
     */
    private $hotelGalleryMapper;

    /**
     * Image manager service
     * 
     * @var \Krystal\Image\Tool\ImageManagerInterface
     */
    private $imageManager;

    /**
     * State initialization
     * 
     * @param \Tour\Storage\HotelGalleryMapperInterface $hotelGalleryMapper
     * @param \Krystal\Image\Tool\ImageManagerInterface $imageManager
     * @return void
     */
    public function __construct(HotelGalleryMapperInterface $hotelGalleryMapper, ImageManagerInterface $imageManager)
    {
        $this->hotelGalleryMapper = $hotelGalleryMapper;
        $this->imageManager = $imageManager;
    }

    /**
     * {@inheritDoc}
     */
    protected function toEntity(array $row)
    {
        $image = new ImageEntity();
        $image->setId($row['id'])
              ->setHotelId($row['hotel_id'])
              ->setOrder($row['order'])
              ->setImage($row['image']);

        // Configure image bag
        $imageBag = clone $this->imageManager->getImageBag();
        $imageBag->setId((int) $row['id'])
                 ->setCover($row['image']);

        $image->setImageBag($imageBag);

        return $image;
    }

    /**
     * Deletes an image by its id
     * 
     * @param int $id
     * @return boolean
     */
    public function deleteById($id)
    {
        return $this->hotelGalleryMapper->deleteByPk($id) && $this->imageManager->delete($id);
    }

    /**
     * Adds an image
     * 
     * @param array $input
     * @return boolean
     */
    public function add(array $input)
    {
        $image = $input['data']['image'];
        $file = $input['files']['file'];

        // Define image attribute
        $image['image'] = $file->getUniqueName();

        // Save image first, because we need to get its ID for image uploading
        $this->hotelGalleryMapper->persist($image);

        // And now upload image
        $this->imageManager->upload($this->getLastId(), $file);

        return true;
    }

    /**
     * Updates an image
     * 
     * @param array $input
     * @return boolean
     */
    public function update(array $input)
    {
        // Grab a reference to image data
        $image = $input['data']['image'];

        // If file new provided, than start handling
        if (!empty($input['files'])) {
            // If we have a previous cover, then we gotta remove it
            $this->imageManager->delete($image['id'], $image['image']);

            $file = $input['files']['file'];

            // Before we start uploading a file, we need to filter its base name
            $this->imageManager->upload($image['id'], $file);

            // Now override cover's value with file's base name we currently have from user's input
            $image['image'] = $file->getUniqueName();
        }

        return $this->hotelGalleryMapper->persist($image);
    }

    /**
     * Fetches an image by its id
     * 
     * @param int $id Image id
     * @return mixed
     */
    public function fetchById($id)
    {
        return $this->prepareResult($this->hotelGalleryMapper->findByPk($id));
    }

    /**
     * Fetch all images
     * 
     * @param int $hotelId
     * @return array
     */
    public function fetchAll($hotelId)
    {
        return $this->prepareResults($this->hotelGalleryMapper->fetchAll($hotelId));
    }

    /**
     * Returns last image id
     * 
     * @return int
     */
    public function getLastId()
    {
        return $this->hotelGalleryMapper->getMaxId();
    }
}
