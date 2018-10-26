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

use Cms\Storage\MySQL\WebPageMapper;
use Cms\Storage\MySQL\AbstractMapper;
use Tour\Storage\TourMapperInterface;

final class TourMapper extends AbstractMapper implements TourMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('bono_module_tour_tours');
    }

    /**
     * {@inheritDoc}
     */
    public static function getTranslationTable()
    {
        return TourTranslationMapper::getTableName();
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
            self::column('order'),
            self::column('seo'),
            self::column('published'),
            self::column('adults'),
            self::column('children'),
            TourTranslationMapper::column('lang_id'),
            TourTranslationMapper::column('web_page_id'),
            TourTranslationMapper::column('name'),
            TourTranslationMapper::column('description'),
            TourTranslationMapper::column('title'),
            TourTranslationMapper::column('meta_keywords'),
            TourTranslationMapper::column('meta_description'),
            WebPageMapper::column('slug')
        );
    }

    /**
     * Attach category IDs
     * 
     * @param int $id Tour ID
     * @param array $categoryIds Category IDs to be attached
     * @return boolean
     */
    public function attachCategories($id, array $categoryIds)
    {
        return $this->syncWithJunction(TourCategoryRelation::getTableName(), $id, $categoryIds);        
    }

    /**
     * Find related category IDs
     * 
     * @param int $id Tour ID
     * @return array
     */
    public function findCategoryIds($id)
    {
        return $this->getSlaveIdsFromJunction(TourCategoryRelation::getTableName(), $id);
    }

    /**
     * Fetches tour data by its associated id
     * 
     * @param string $id Tour id
     * @param boolean $withTranslations Whether to fetch translations or not
     * @return array
     */
    public function fetchById($id, $withTranslations)
    {
        return $this->findWebPage($this->getColumns(), $id, $withTranslations);
    }

    /**
     * {@inheritDoc}
     */
    public function filter($input, $page, $itemsPerPage, $sortingColumn, $desc, array $parameters = array())
    {
        if (!$sortingColumn) {
            $sortingColumn = self::column('id');
        }

        $db = $this->createWebPageSelect($this->getColumns())
                    // Filtering condition
                    ->whereEquals(TourTranslationMapper::column('lang_id'), $this->getLangId())
                    ->orderBy(array($sortingColumn => $desc ? 'DESC' : 'ASC'))
                    ->paginate($page, $itemsPerPage);

        return $db->queryAll();
    }
}
