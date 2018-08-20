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
}
