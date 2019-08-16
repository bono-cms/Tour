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
use Tour\Storage\HotelGalleryMapperInterface;

/**
 * Shared mapper for those items that have attached image gallery
 */
abstract class AbstractGalleryMapper extends AbstractMapper
{
    /**
     * Fetch all tour images
     * 
     * @param string $column
     * @param int $value Attached ID
     * @param boolean $sort Whether to sort by corresponding sorting order
     * @return array
     */
    final protected function findAllImages($column, $value, $sort)
    {
        $db = $this->db->select('*')
                       ->from(static::getTableName())
                       ->whereEquals(static::column($column), $value);

        if ($sort === false) {
            // Sort by last IDs
            $db->orderBy(static::column('id'))
               ->desc();
        } else {
            $db->orderBy(array(
                static::column('order'), 
                new RawSqlFragment(sprintf('CASE WHEN %s = 0 THEN %s END DESC', static::column('order'), static::column('id')))
            ));
        }

        return $db->queryAll();
    }
}
