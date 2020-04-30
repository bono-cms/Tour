<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Tour\Storage\MySQL;

use Cms\Storage\MySQL\AbstractStorageDropper;

final class Dropper extends AbstractStorageDropper
{
    /**
     * {@inheritDoc}
     */
    protected function getTables()
    {
        return array(
            TourMapper::getTableName(),
            TourPricePolicyMapper::getTableName()
            TourTranslationMapper::getTableName(),
            TourDayMapper::getTableName(),
            TourDayTranslationMapper::getTableName(),
            TourDateMapper::getTableName(),
            TourGalleryMapper::getTableName(),
            TourCategoryRelation::getTableName(),
            TourRelatedRelation::getTableName(),
            TourBookingMapper::getTableName(),
            TourReviewMapper::getTableName(),
            TourDestinationMapper::getTableName(),
            TourDestinationTranslationMapper::getTableName(),
            CategoryMapper::getTableName(),
            CategoryTranslationMapper::getTableName(),
            HotelMapper::getTableName(),
            HotelTranslationMapper::getTableName(),
            TourHotelRelationMapper::getTableName(),
            HotelGalleryMapper::getTableName()
        );
    }
}
