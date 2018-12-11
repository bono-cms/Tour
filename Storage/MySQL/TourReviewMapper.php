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
     * @param boolean $published Whether fetch only published ones or not
     * @param int|null $page Current page number
     * @param int|null $itemsPerPage Per page count
     * @return array
     */
    public function fetchAll($tourId = null, $published, $page = null, $itemsPerPage = null)
    {
        // To be selected
        $columns = array(
            self::column('id'),
            self::column('tour_id'),
            self::column('datetime'),
            self::column('name'),
            self::column('message'),
            self::column('published'),
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
            $db->andWhereEquals(self::column('tour_id'), $tourId);
        }

        // Fetch only published ones?
        if ($published) {
            $db->andWhereEquals(self::column('published'), 1);
        }

        // Sort by latest
        $db->orderBy(self::column('id'));

        // Apply pagination if required
        if ($page !== null && $itemsPerPage !== null) {
            $db->paginate($page, $itemsPerPage);
        }

        return $db->queryAll();
    }
}
