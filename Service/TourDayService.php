<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Tour\Service;

use Tour\Storage\TourDayMapperInterface;

final class TourDayService
{
    /**
     * Tour day mapper
     * 
     * @var \Tour\Storage\TourDayMapperInterface
     */
    private $tourDayMapper;

    /**
     * State initialization
     * 
     * @param \Tour\Storage\TourDayMapperInterface $tourDayMapper
     * @return void
     */
    public function __construct(TourDayMapperInterface $tourDayMapper)
    {
        $this->tourDayMapper = $tourDayMapper;
    }

    /**
     * Returns last id
     * 
     * @return int
     */
    public function getLastId()
    {
        return $this->tourDayMapper->getMaxId();
    }
}
