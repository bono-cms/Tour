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
        );
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
