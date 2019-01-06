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
use Cms\Storage\MySQL\WebPageMapper;
use Cms\Storage\MySQL\AbstractMapper;
use Tour\Storage\CategoryMapperInterface;

final class CategoryMapper extends AbstractMapper implements CategoryMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('bono_module_tour_category');
    }

    /**
     * {@inheritDoc}
     */
    public static function getTranslationTable()
    {
        return CategoryTranslationMapper::getTableName();
    }

    /**
     * Return columns to be selected
     * 
     * @return array
     */
    private function getColumns()
    {
        return array(
            self::column('id'),
            self::column('order'),
            self::column('seo'),
            self::column('cover'),
            CategoryTranslationMapper::column('lang_id'),
            CategoryTranslationMapper::column('web_page_id'),
            CategoryTranslationMapper::column('name'),
            CategoryTranslationMapper::column('description'),
            CategoryTranslationMapper::column('title'),
            CategoryTranslationMapper::column('meta_keywords'),
            CategoryTranslationMapper::column('meta_description'),
            WebPageMapper::column('slug')
        );
    }

    /**
     * Fetches category data by its associated id
     * 
     * @param string $id Category id
     * @param boolean $withTranslations Whether to fetch translations or not
     * @return array
     */
    public function fetchById($id, $withTranslations)
    {
        return $this->findWebPage($this->getColumns(), $id, $withTranslations);
    }

    /**
     * Fetches all categories
     * 
     * @return array
     */
    public function fetchAll()
    {
        $columns = $this->getColumns();
        $columns[] = new RawSqlFragment(sprintf('COUNT(%s) AS tour_count', TourCategoryRelation::column('master_id')));

        $db = $this->createWebPageSelect($columns)
                    // Tour -> category relation
                    ->leftJoin(TourCategoryRelation::getTableName(), array(
                        TourCategoryRelation::column('slave_id') => self::getRawColumn($this->getPk())
                    ))
                    ->whereEquals(CategoryTranslationMapper::column('lang_id'), $this->getLangId())
                    ->groupBy($this->getColumns())
                    ->orderBy(self::column($this->getPk()))
                    ->desc();

        return $db->queryAll();
    }
}
