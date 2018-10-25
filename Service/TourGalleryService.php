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

use Krystal\Image\Tool\ImageManagerInterface;
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
     * Image manager service
     * 
     * @var \Krystal\Image\Tool\ImageManagerInterface
     */
    private $imageManager;

    /**
     * State initialization
     * 
     * @param \Tour\Storage\TourGalleryMapperInterface $tourGalleryMapper
     * @param \Krystal\Image\Tool\ImageManagerInterface $imageManager
     * @return void
     */
    public function __construct(TourGalleryMapperInterface $tourGalleryMapper, ImageManagerInterface $imageManager)
    {
        $this->tourGalleryMapper = $tourGalleryMapper;
        $this->imageManager = $imageManager;
    }

    /**
     * {@inheritDoc}
     */
    protected function toEntity(array $row)
    {
        $image = new ImageEntity();
        $image->setId($row['id'])
              ->setTourId($row['tour_id'])
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
        return $this->tourGalleryMapper->deleteByPk($id) && $this->imageManager->delete($id);
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

        // Filter files input
        $this->filterFileInput($file);

        // Define image attribute
        $image['image'] = $file[0]->getName();

        // Save image first, because we need to get its ID for image uploading
        $this->tourGalleryMapper->persist($image);

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
            $this->filterFileInput($file);
            $this->imageManager->upload($image['id'], $file);

            // Now override cover's value with file's base name we currently have from user's input
            $image['image'] = $file[0]->getName();
        }

        return $this->tourGalleryMapper->persist($image);
    }
}
