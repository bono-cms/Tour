<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

return array(
    '/%s/module/tour' => array(
        'controller' => 'Admin:Grid@indexAction'
    ),

    // Category
    '/%s/module/tour/category/save' => array(
        'controller' => 'Admin:Category@saveAction'
    ),
    
    '/%s/module/tour/category/add' => array(
        'controller' => 'Admin:Category@addAction'
    ),
    
    '/%s/module/tour/category/edit/(:var)' => array(
        'controller' => 'Admin:Category@editAction'
    ),

    '/%s/module/tour/category/delete/(:var)' => array(
        'controller' => 'Admin:Category@deleteAction'
    ),

    // Tours
    '/%s/module/tour/add' => array(
        'controller' => 'Admin:Tour@addAction'
    ),
    
    '/%s/module/tour/edit/(:var)' => array(
        'controller' => 'Admin:Tour@editAction'
    ),
    
    '/%s/module/tour/save' => array(
        'controller' => 'Admin:Tour@saveAction'
    ),

    '/%s/module/tour/delete/(:var)' => array(
        'controller' => 'Admin:Tour@deleteAction'
    )
);
