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

final class TourEntity extends VirtualEntity
{
    /**
     * Checks whether current tour entity has language constraint
     * 
     * @return boolean
     */
    public function hasConstraintLanguageId()
    {
        return (bool) $this->getLangConstraintId();
    }

    /**
     * Checks whether target language id is a constraint one
     * 
     * @param int $langId
     * @return boolean
     */
    public function isConstraintLanguageId($langId)
    {
        $constraintId = $this->getLangConstraintId();

        if ($constraintId == 0) {
            return false;
        }

        return $langId == $constraintId;
    }

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
     * Checks whether current tour has related ones
     * 
     * @return boolean
     */
    public function hasRelatedTours()
    {
        return $this->isFilled($this->getRelatedIds());
    }

    /**
     * Checks whether current tour has attached hotels
     * 
     * @return boolean
     */
    public function hasHotels()
    {
        return $this->isFilled($this->getHotels());
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
     * Returns start dates
     * 
     * @return array
     */
    public function getStartDates()
    {
        $output = array();

        if ($this->hasDates()) {
            foreach ($this->getDates() as $date) {
                $output[] = $date->getStart();
            }

            $output = ArrayUtils::valuefy($output);
        }

        return $output;
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
