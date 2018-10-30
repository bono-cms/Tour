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

use Tour\Storage\TourReviewMapperInterface;
use Cms\Service\AbstractManager;

final class TourReviewService extends AbstractManager
{
    /**
     * Tour review mapper
     * 
     * @var \Tour\Storage\TourReviewMapperInterface
     */
    private $tourReviewMapper;

    /**
     * State initialization
     * 
     * @param \Tour\Storage\TourReviewMapperInterface
     * @return void
     */
    public function __construct(TourReviewMapperInterface $tourReviewMapper)
    {
        $this->tourReviewMapper = $tourReviewMapper;
    }
}
