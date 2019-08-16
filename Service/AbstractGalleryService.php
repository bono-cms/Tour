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
use Cms\Service\AbstractManager;
use Tour\Storage\SharedGalleryMapperInterface;

abstract class AbstractGalleryService extends AbstractManager
{
    /**
     * Any tour gallery mapper
     * 
     * @var \Tour\Storage\SharedGalleryMapperInterface
     */
    protected $galleryMapper;

    /**
     * Image manager service
     * 
     * @var \Krystal\Image\Tool\ImageManagerInterface
     */
    protected $imageManager;

    /**
     * State initialization
     * 
     * @param \Tour\Storage\SharedGalleryMapperInterface $galleryMapper
     * @param \Krystal\Image\Tool\ImageManagerInterface $imageManager
     * @return void
     */
    public function __construct(SharedGalleryMapperInterface $galleryMapper, ImageManagerInterface $imageManager)
    {
        $this->galleryMapper = $galleryMapper;
        $this->imageManager = $imageManager;
    }

    /**
     * Returns last image ID
     * 
     * @return int
     */
    public function getLastId()
    {
        return $this->galleryMapper->getMaxId();
    }

    /**
     * Fetch images as a list
     * 
     * @param int $tourId
     * @param string $size Desired size of images
     * @return array
     */
    public function fetchImages($tourId, $size)
    {
        // To be returned
        $output = array();
        $images = $this->fetchAll($tourId, true);

        foreach ($images as $image) {
            $output[] = $image->getImageUrl($size);
        }

        return $output;
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
        return $this->prepareResults($this->galleryMapper->fetchAll($tourId, $sort));
    }

    /**
     * Fetch image by its ID
     * 
     * @param int $id Image ID
     * @return mixed
     */
    public function fetchById($id)
    {
        return $this->prepareResult($this->galleryMapper->findByPk($id));
    }

    /**
     * Deletes an image by its ID
     * 
     * @param int $id Image ID
     * @return boolean
     */
    public function deleteById($id)
    {
        return $this->galleryMapper->deleteByPk($id) && $this->imageManager->delete($id);
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
        $this->galleryMapper->persist($image);

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

        return $this->galleryMapper->persist($image);
    }
}
