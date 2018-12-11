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

final class TourEntity extends VirtualEntity
{
    /**
     * Returns count of available nights
     * 
     * @return integer
     */
    public function getNightsCount()
    {
        $daysCount = $this->getDaysCount();

        if ($daysCount > 0) {
            return $daysCount - 1;
        } else {
            return 0;
        }
    }

    /**
     * Returns count of available days
     * 
     * @return integer
     */
    public function getDaysCount()
    {
        $days = $this->getDays();
        return count($days);
    }

    /**
     * Returns review count
     * 
     * @return integer
     */
    public function getReviewCount()
    {
        $reviews = $this->getReviews();
        return count($reviews);
    }

    /**
     * Checks whether collection is filled
     * 
     * @param array $data
     * @return boolean
     */
    private function isFilled(array $data)
    {
        return !empty($data);
    }

    /**
     * Checks whether there's at least one review
     * 
     * @return boolean
     */
    public function hasReviews()
    {
        return $this->isFilled($this->getReviews());
    }

    /**
     * Checks whether there's at least one gallery image uploaded
     * 
     * @return boolean
     */
    public function hasGallery()
    {
        return $this->isFilled($this->getGallery());
    }

    /**
     * Checks whether there are more than one gallery image uploaded
     * 
     * @return boolean
     */
    public function hasGalleryControls()
    {
        $images = $this->getGallery();
        return count($images) > 1;
    }

    /**
     * Checks whether there's at least one attached date
     * 
     * @return boolean
     */
    public function hasDates()
    {
        return $this->isFilled($this->getDates());
    }

    /**
     * Checks whether there's at least one attached day
     * 
     * @return array
     */
    public function hasDays()
    {
        return $this->isFilled($this->getDays());
    }

    /**
     * Returns a path to image URL
     * 
     * @param string $size
     * @return string
     */
    public function getImageUrl($size)
    {
        return $this->getImageBag()->getUrl($size);
    }

    /**
     * Checks whether current tour has a price
     * 
     * @return boolean
     */
    public function hasPrice()
    {
        $price = $this->getPrice();
        return $price && $price > 0;
    }

    /**
     * Checks whether current tour has a starting price
     * 
     * @return boolean
     */
    public function hasStartPrice()
    {
        $price = $this->getStartPrice();
        return $price && $price > 0;
    }
}
