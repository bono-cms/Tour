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
use Tour\Storage\TourGalleryMapperInterface;

final class TourGalleryMapper extends AbstractMapper implements TourGalleryMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('bono_module_tour_gallery');
    }

    /**
     * Fetch all tour images
     * 
     * @param int $tourId Attached tour ID
     * @param boolean $sort Whether to sort by corresponding sorting order
     * @return array
     */
    public function fetchAll($tourId, $sort)
    {
        $db = $this->db->select('*')
                       ->from(self::getTableName())
                       ->whereEquals(self::column('tour_id'), $tourId);

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
}
