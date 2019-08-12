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

final class TourGalleryService extends AbstractGalleryService
{
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
}
