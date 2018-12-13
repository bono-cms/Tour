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
use Tour\Storage\TourDestinationMapperInterface;

final class TourDestinationMapper extends AbstractMapper implements TourDestinationMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('bono_module_tour_destinations');
    }

    /**
     * {@inheritDoc}
     */
    public static function getTranslationTable()
    {
        return TourDestinationTranslationMapper::getTableName();
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
            TourDestinationTranslationMapper::column('lang_id'),
            TourDestinationTranslationMapper::column('name')
        );
    }

    /**
     * Fetch tour destination by its ID
     * 
     * @param int $id Tour destination ID
     * @param boolean $withTranslations Whether to fetch translations or not
     * @return mixed
     */
    public function fetchById($id, $withTranslations)
    {
        return $this->findEntity($this->getColumns(), $id, $withTranslations);
    }
}
