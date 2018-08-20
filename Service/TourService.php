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

use Cms\Service\WebPageManagerInterface;
use Cms\Service\AbstractManager;
use Tour\Storage\TourMapperInterface;

final class TourService extends AbstractManager
{
    /**
     * Tour mapper
     * 
     * @var \Tour\Storage\TourMapperInterface
     */
    private $tourMapper;

    /**
     * Web page service
     * 
     * @var \Cms\Service\WebPageManagerInterface
     */
    private $webPageManager;

    /**
     * State initialization
     * 
     * @param \Tour\Storage\TourMapperInterface $tourMapper
     * @param \Cms\Service\WebPageManagerInterface $webPageManager
     * @return void
     */
    public function __construct(TourMapperInterface $tourMapper, WebPageManagerInterface $webPageManager)
    {
        $this->tourMapper = $tourMapper;
        $this->webPageManager = $webPageManager;
    }
}
