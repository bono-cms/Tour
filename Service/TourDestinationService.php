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
use Tour\Storage\TourDestinationMapperInterface;

final class TourDestinationService extends AbstractManager implements TourDestinationMapperInterface
{
    /**
     * Any compliant tour destination mapper
     * 
     *  @var \Tour\Storage\TourDestinationMapperInterface
     */
    private $tourDestinationMapper;

    /**
     * State initialization
     * 
     * @param \Tour\Storage\TourDestinationMapperInterface $tourDestinationMapper
     * @return void
     */
    public function __construct(TourDestinationMapperInterface $tourDestinationMapper)
    {
        $this->tourDestinationMapper = $tourDestinationMapper;
    }

    /**
     * Returns last tour destination ID
     * 
     * @return int
     */
    public function getLastId()
    {
        return $this->tourDestinationMapper->getMaxId();
    }
}
