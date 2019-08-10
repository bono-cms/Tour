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
use Cms\Storage\MySQL\WebPageMapper;
use Tour\Storage\HotelMapperInterface;

final class HotelMapper extends AbstractMapper implements HotelMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('bono_module_tour_hotels');
    }

    /**
     * {@inheritDoc}
     */
    public static function getTranslationTable()
    {
        return HotelTranslationMapper::getTableName();
    }

    /**
     * Returns shared columns to be selected
     * 
     * @return array
     */
    private function getColumns()
    {
        return array(
            self::column('id'),
            self::column('order'),
            HotelTranslationMapper::column('web_page_id'),
            HotelTranslationMapper::column('lang_id'),
            HotelTranslationMapper::column('name'),
            HotelTranslationMapper::column('description'),
            HotelTranslationMapper::column('title'),
            HotelTranslationMapper::column('meta_keywords'),
            HotelTranslationMapper::column('meta_description'),
            WebPageMapper::column('slug')
        );
    }

    /**
     * Find attached hotels by tour id
     * 
     * @param int $id Tour id
     * @return array
     */
    public function findHotelsByTourId($id)
    {
        // Columns to be selected
        $columns = array(
            self::column('id'),
            HotelTranslationMapper::column('lang_id'),
            HotelTranslationMapper::column('name'),
            WebPageMapper::column('slug')
        );

        $db = $this->db->select($columns)
                       ->from(TourHotelRelationMapper::getTableName())
                       // Hotel relation
                       ->leftJoin(self::getTableName(), array(
                            self::column('id') => TourHotelRelationMapper::getRawColumn('slave_id')
                       ))
                       // Hotel translation relation
                       ->leftJoin(HotelTranslationMapper::getTableName(), array(
                            HotelTranslationMapper::column('id') => self::getRawColumn('id')
                       ))
                       // Web page relation
                       ->leftJoin(WebPageMapper::getTableName(), array(
                            WebPageMapper::column('id') => HotelTranslationMapper::getRawColumn('web_page_id'),
                            WebPageMapper::column('lang_id') => HotelTranslationMapper::getRawColumn('lang_id')
                       ))
                       ->whereEquals(TourHotelRelationMapper::column('master_id'), $id)
                       ->andWhereEquals(HotelTranslationMapper::column('lang_id'), $this->getLangId());

        return $db->queryAll();
    }

    /**
     * Fetch all hotels
     * 
     * @param boolean $sort Whether to sort by corresponding sorting order
     * @return array
     */
    public function fetchAll($sort)
    {
        $db = $this->createWebPageSelect($this->getColumns())
                   // Language ID constraint
                   ->whereEquals(HotelTranslationMapper::column('lang_id'), $this->getLangId());

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

        return $db->queryAll();
    }

    /**
     * Fetches a hotel by its associated id
     * 
     * @param string $id
     * @param boolean $withTranslations Whether to fetch translations or not
     * @return array
     */
    public function fetchById($id, $withTranslations)
    {
        return $this->findWebPage($this->getColumns(), $id, $withTranslations);
    }
}
