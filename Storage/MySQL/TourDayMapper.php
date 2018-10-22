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

use Krystal\Db\Sql\RawSqlFragment;
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
     * Fetch all tour days
     * 
     * @param int $tourId Attached tour ID
     * @param boolean $sort Whether to sort by corresponding sorting order
     * @return array
     */
    public function fetchAll($tourId, $sort)
    {
        $db = $this->createEntitySelect($this->getColumns())
                   // Language ID constraint
                   ->whereEquals(TourDayTranslationMapper::column('lang_id'), $this->getLangId())
                   ->andWhereEquals(self::column('tour_id'), $tourId);

        if ($sort === false) {
            // Sort by last IDs
            $db->orderBy(self::column('id'))
               ->desc();
        } else {
            $db->orderBy(array(
                self::column('order'), 
                new RawSqlFragment(sprintf('CASE WHEN %s = 0 THEN %s END DESC', self::column('order'), self::column('id')))
            ));
        }

        //echo $db;exit;
        
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
