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

use Cms\Service\AbstractManager;
use Tour\Storage\TourDateMapperInterface;

final class TourDateService extends AbstractManager
{
    /**
     * Tour date mapper
     * 
     * @var \Tour\Storage\TourDateMapperInterface
     */
    private $tourDateMapper;

    /**
     * State initialization
     * 
     * @param \Tour\Storage\TourDateMapperInterface $tourDateMapper
     * @return void
     */
    public function __construct(TourDateMapperInterface $tourDateMapper)
    {
        $this->tourDateMapper = $tourDateMapper;
    }
}
