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

use Cms\Storage\MySQL\AbstractMapper;
use Tour\Storage\TourReviewMapperInterface;

final class TourReviewMapper extends AbstractMapper implements TourReviewMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('bono_module_tour_reviews');
    }

    /**
     * Fetch all tour reviews by its ID
     * 
     * @param int|null $tourId
     * @return array
     */
    public function fetchAll($tourId = null)
    {
        // To be selected
        $columns = array(
            self::column('id'),
            self::column('tour_id'),
            self::column('datetime'),
            self::column('name'),
            self::column('message'),
            TourTranslationMapper::column('name') => 'tour'
        );

        $db = $this->db->select($columns)
                       ->from(self::getTableName())
                       // Tour relation
                       ->innerJoin(TourMapper::getTableName(), array(
                            TourMapper::column('id') => self::getRawColumn('tour_id')
                       ))
                       // Tour translation relation
                       ->leftJoin(TourTranslationMapper::getTableName(), array(
                            TourTranslationMapper::column('id') => TourMapper::getRawColumn('id')
                       ))
                       // Constraints
                       ->whereEquals(TourTranslationMapper::column('lang_id'), $this->getLangId());

        if ($tourId !== null) {
            $db->whereEquals(self::column('tour_id'), $tourId);
        }

        return $db->queryAll();
    }
}
