<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Tour\Controller;

use Site\Controller\AbstractController;

final class Tour extends AbstractController
{
    /**
     * Renders tour template
     * 
     * @param int $id Tour ID
     * @return string
     */
    public function tourAction($id)
    {
        
    }

    /**
     * Renders category template
     * 
     * @param int $id Category ID
     * @param integer $pageNumber current page number
     * @param string $code Language code
     * @param string $slug Category page's slug
     * @return string
     */
    public function categoryAction($id = false, $pageNumber = 1, $code = null, $slug = null)
    {
        
    }
}
