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
    // Payment
    '/module/tour/payment/gateway/(:var)' => array(
        'controller' => 'Payment@gatewayAction'
    ),

    '/module/tour/payment/success/(:var)' => array(
        'controller' => 'Payment@successAction'
    ),

    '/%s/module/tour' => array(
        'controller' => 'Admin:Grid@indexAction'
    ),

    // Booking
    '/%s/module/tour/booking/index' => array(
        'controller' => 'Admin:Booking@indexAction'
    ),

    '/%s/module/tour/booking/save' => array(
        'controller' => 'Admin:Booking@saveAction'
    ),

    '/%s/module/tour/booking/add' => array(
        'controller' => 'Admin:Booking@addAction'
    ),

    '/%s/module/tour/booking/edit/(:var)' => array(
        'controller' => 'Admin:Booking@editAction'
    ),

    '/%s/module/tour/booking/delete/(:var)' => array(
        'controller' => 'Admin:Booking@deleteAction'
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
    ),

    // Tour days
    '/%s/module/tour/day/add/(:var)' => array(
        'controller' => 'Admin:TourDay@addAction'
    ),

    '/%s/module/tour/day/edit/(:var)' => array(
        'controller' => 'Admin:TourDay@editAction'
    ),

    '/%s/module/tour/day/save' => array(
        'controller' => 'Admin:TourDay@saveAction'
    ),

    '/%s/module/tour/day/delete/(:var)' => array(
        'controller' => 'Admin:TourDay@deleteAction'
    ),

    // Tour gallery
    '/%s/module/tour/gallery/add/(:var)' => array(
        'controller' => 'Admin:TourGallery@addAction'
    ),

    '/%s/module/tour/gallery/edit/(:var)' => array(
        'controller' => 'Admin:TourGallery@editAction'
    ),

    '/%s/module/tour/gallery/save' => array(
        'controller' => 'Admin:TourGallery@saveAction'
    ),

    '/%s/module/tour/gallery/delete/(:var)' => array(
        'controller' => 'Admin:TourGallery@deleteAction'
    )
);
