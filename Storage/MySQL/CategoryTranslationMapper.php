<?php

namespace Tour\Storage\MySQL;

use Cms\Storage\MySQL\AbstractMapper;

final class CategoryTranslationMapper extends AbstractMapper
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('bono_module_tour_category_translation');
    }
}
