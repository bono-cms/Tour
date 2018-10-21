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
use Tour\Storage\TourDayMapperInterface;

final class TourDayMapper extends AbstractMapper implements TourDayMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('bono_module_tour_tours_days');
    }

    /**
     * {@inheritDoc}
     */
    public static function getTranslationTable()
    {
        return TourDayTranslationMapper::getTableName();
    }

    /**
     * Returns columns to be selected
     * 
     * @return array
     */
    private function getColumns()
    {
        return array(
            self::column('id'),
            self::column('tour_id'),
            self::column('order'),
            TourDayTranslationMapper::column('lang_id'),
            TourDayTranslationMapper::column('title'),
            TourDayTranslationMapper::column('description')
        );
    }

    /**
     * Fetch all items
     * 
     * @param int $tourId Attached tour ID
     * @return array
     */
    public function fetchAll($tourId)
    {
        $db = $this->createEntitySelect($this->getColumns())
                   // Language ID constraint
                   ->whereEquals(TourDayTranslationMapper::column('lang_id'), $this->getLangId())
                   ->andWhereEquals(self::column('tour_id'), $tourId)
                   // Sort by last IDs
                   ->orderBy(self::column('id'))
                   ->desc();

        return $db->queryAll();
    }

    /**
     * Fetches tour day by its associated id
     * 
     * @param string $id
     * @param boolean $withTranslations Whether to fetch translations or not
     * @return array
     */
    public function fetchById($id, $withTranslations)
    {
        return $this->findEntity($this->getColumns(), $id, $withTranslations);
    }
}
