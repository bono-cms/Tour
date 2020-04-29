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

use Krystal\Stdlib\VirtualEntity;
use Cms\Service\AbstractManager;
use Tour\Storage\TourPricePolicyMapperInterface;

final class TourPricePolicyService extends AbstractManager
{
    /**
     * Tour price policy mapper
     * 
     * @var \Tour\Storage\TourPricePolicyMapperInterface
     */
    private $policyMapper;

    /**
     * State initialization
     * 
     * @param \Tour\Storage\TourPricePolicyMapperInterface $policyMapper
     * @return void
     */
    public function __construct(TourPricePolicyMapperInterface $policyMapper)
    {
        $this->policyMapper = $policyMapper;
    }
}
