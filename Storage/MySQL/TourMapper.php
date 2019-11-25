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

use Krystal\Db\Filter\InputDecorator;
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
            self::column('destination_id'),
            self::column('lang_constraint_id'),
            self::column('order'),
            self::column('seo'),
            self::column('published'),
            self::column('adults'),
            self::column('children'),
            self::column('recommended'),
            self::column('price'),
            self::column('start_price'),
            self::column('cover'),
            self::column('views'),
            TourTranslationMapper::column('lang_id'),
            TourTranslationMapper::column('web_page_id'),
            TourTranslationMapper::column('name'),
            TourTranslationMapper::column('short'),
            TourTranslationMapper::column('description'),
            TourTranslationMapper::column('included'),
            TourTranslationMapper::column('excluded'),
            TourTranslationMapper::column('title'),
            TourTranslationMapper::column('meta_keywords'),
            TourTranslationMapper::column('meta_description'),
            WebPageMapper::column('slug'),
            WebPageMapper::column('changefreq'),
            WebPageMapper::column('priority')
        );
    }

    /**
     * Increments view count by tour id
     * 
     * @param string $id Tour ID
     * @return boolean
     */
    public function incrementViewCount($id)
    {
        return $this->incrementColumnByPk($id, 'views');
    }

    /**
     * Attach related tours
     * 
     * @param int $id Main tour ID
     * @param array $tourIds Related tour IDs to be attached
     * @return boolean
     */
    public function attachRelatedTours($id, array $tourIds)
    {
        return $this->syncWithJunction(TourRelatedRelation::getTableName(), $id, $tourIds);
    }

    /**
     * Find related category IDs
     * 
     * @param int $id Tour ID
     * @return array
     */
    public function findRelatedIds($id)
    {
        return $this->getSlaveIdsFromJunction(TourRelatedRelation::getTableName(), $id);
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
     * Attach hotel ids
     * 
     * @param int $id Tour id
     * @param array $hotelIds
     * @return boolean
     */
    public function attachHotels($id, array $hotelIds)
    {
        return $this->syncWithJunction(TourHotelRelationMapper::getTableName(), $id, $hotelIds);
    }

    /**
     * Find attached hotel ids
     * 
     * @param int $id Tour id
     * @return array
     */
    public function findHotelIds($id)
    {
        return $this->getSlaveIdsFromJunction(TourHotelRelationMapper::getTableName(), $id);
    }

    /**
     * Fetch all available tours
     * 
     * @return array
     */
    public function fetchList()
    {
        // Columns to be selected
        $columns = array(
            self::column('id'),
            TourTranslationMapper::column('name')
        );

        return $this->createEntitySelect($columns)
                    ->whereEquals(TourTranslationMapper::column('lang_id'), $this->getLangId())
                    ->orderBy(self::column('id'))
                    ->desc()
                    ->queryAll();
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
        if (!($input instanceof InputDecorator)) {
            $input = new InputDecorator($input);
        }

        // Available sorting methods
        $sortingMethods = array(
            'name' => TourTranslationMapper::column('name'),
            'category_id' => CategoryMapper::column('id'),
            'adults' => self::column('adults'),
            'published' => self::column('published')
        );

        // Column to be sorted
        $sortingColumn = isset($sortingMethods[$sortingColumn]) ? $sortingMethods[$sortingColumn] : self::column('id');

        // Whether category ID filter provided
        $hasCategory = !empty($input['category_id']);
        $hasDate = !empty($input['begin']);

        // Columns to be selected
        $columns = $this->getColumns();

        // Append extra columns
        $columns[TourCategoryRelation::column('slave_id')] = 'category_id';
        $columns[CategoryTranslationMapper::column('name')] = 'category';

        $db = $this->createWebPageSelect($columns)
                   // Tour category junction relation
                   ->leftJoin(TourCategoryRelation::getTableName(), array(
                        TourCategoryRelation::column('master_id') => self::getRawColumn('id')
                   ))
                   // Category relation
                   ->leftJoin(CategoryMapper::getTableName(), array(
                        CategoryMapper::column('id') => TourCategoryRelation::getRawColumn('slave_id')
                   ))
                   // Category translation relation
                   ->leftJoin(CategoryTranslationMapper::getTableName(), array(
                        CategoryTranslationMapper::column('id') => CategoryMapper::getRawColumn('id'),
                        CategoryTranslationMapper::column('lang_id') => TourTranslationMapper::getRawColumn('lang_id')
                   ));

        // Optional date constraint
        if ($hasDate) {
            $db->innerJoin(TourDateMapper::getTableName(), array(
                TourDateMapper::column('tour_id') => self::getRawColumn('id')
            ));
        }

        // Filtering condition
        $db->whereEquals(TourTranslationMapper::column('lang_id'), $this->getLangId());

        if ($hasCategory) {
            $db->andWhereEquals(TourCategoryRelation::column('slave_id'), $input['category_id']);
        }

        // If date provided, then apply it
        $db->andWhereEquals(TourDateMapper::column('start'), $input['begin'], true)
            ->andWhereEquals(self::column('adults'), $input['adults'], true)
            ->andWhereEquals(self::column('children'), $input['children'], true)
            ->andWhereEquals(self::column('destination_id'), $input['destination_id'], true)
            ->andWhereLike(TourTranslationMapper::column('name'), '%' . $input['name'] . '%', true)
            ->andWhereEquals(self::column('published'), $input['published'], true)
            ->andWhereEquals(self::column('recommended'), $input['recommended'], true);

        $db->orderBy(array($sortingColumn => $desc ? 'DESC' : 'ASC'));

        // Apply pagination on demand
        if ($page !== null && $itemsPerPage !== null) {
            $db->paginate($page, $itemsPerPage);
        }

        return $db->queryAll();
    }
}
