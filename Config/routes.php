<?php

/**
 * This file is part of the Bono CMS
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

return [
    '/module/tour/generate-price' => [
        'controller' => 'Tour@priceAction'
    ],

    '/module/tour/search/(:var)' => [
        'controller' => 'Tour@searchAction'
    ],

    '/module/tour/recommended' => [
        'controller' => 'Tour@recommendedAction'
    ],

    // Payment
    '/module/tour/payment/gateway/(:var)' => [
        'controller' => 'Payment@gatewayAction'
    ],

    '/module/tour/payment/success/(:var)' => [
        'controller' => 'Payment@successAction'
    ],

    '/module/tour/payment/invoice' => [
        'controller' => 'Payment@invoiceAction'
    ],

    '/module/tour/payment/book/(:var)' => [
        'controller' => 'Tour@bookAction'
    ],

    '/%s/module/tour' => [
        'controller' => 'Admin:Grid@indexAction'
    ],

    '/%s/module/tour/filter/(:var)' => [
        'controller' => 'Admin:Grid@indexAction'
    ],

    // Reviews
    '/module/tour/reviews/new' => [
        'controller' => 'Tour@reviewAction'
    ],
    
    '/%s/module/tour/reviews/(:var)' => [
        'controller' => 'Admin:TourReview@indexAction'
    ],

    '/%s/module/tour/reviews/approve/(:var)' => [
        'controller' => 'Admin:TourReview@approveAction'
    ],

    '/%s/module/tour/reviews/delete/(:var)' => [
        'controller' => 'Admin:TourReview@deleteAction'
    ],
    
    // Booking
    '/%s/module/tour/booking/notify/(:var)' => [
        'controller' => 'Admin:Booking@notifyAction'
    ],

    '/%s/module/tour/booking/index/(:var)' => [
        'controller' => 'Admin:Booking@indexAction'
    ],

    '/%s/module/tour/booking/save' => [
        'controller' => 'Admin:Booking@saveAction'
    ],

    '/%s/module/tour/booking/add' => [
        'controller' => 'Admin:Booking@addAction'
    ],

    '/%s/module/tour/booking/edit/(:var)' => [
        'controller' => 'Admin:Booking@editAction'
    ],

    '/%s/module/tour/booking/delete/(:var)' => [
        'controller' => 'Admin:Booking@deleteAction'
    ],

    // Category
    '/%s/module/tour/category/save' => [
        'controller' => 'Admin:Category@saveAction'
    ],
    
    '/%s/module/tour/category/add' => [
        'controller' => 'Admin:Category@addAction'
    ],
    
    '/%s/module/tour/category/edit/(:var)' => [
        'controller' => 'Admin:Category@editAction'
    ],

    '/%s/module/tour/category/delete/(:var)' => [
        'controller' => 'Admin:Category@deleteAction'
    ],

    // Tours
    '/%s/module/tour/add' => [
        'controller' => 'Admin:Tour@addAction'
    ],
    
    '/%s/module/tour/edit/(:var)' => [
        'controller' => 'Admin:Tour@editAction'
    ],
    
    '/%s/module/tour/save' => [
        'controller' => 'Admin:Tour@saveAction'
    ],

    '/%s/module/tour/delete/(:var)' => [
        'controller' => 'Admin:Tour@deleteAction'
    ],

    // Tour dates
    '/%s/module/tour/date/add/(:var)' => [
        'controller' => 'Admin:TourDate@addAction'
    ],

    '/%s/module/tour/date/edit/(:var)' => [
        'controller' => 'Admin:TourDate@editAction'
    ],

    '/%s/module/tour/date/save' => [
        'controller' => 'Admin:TourDate@saveAction'
    ],

    '/%s/module/tour/date/delete/(:var)' => [
        'controller' => 'Admin:TourDate@deleteAction'
    ],

    // Tour days
    '/%s/module/tour/day/add/(:var)' => [
        'controller' => 'Admin:TourDay@addAction'
    ],

    '/%s/module/tour/day/edit/(:var)' => [
        'controller' => 'Admin:TourDay@editAction'
    ],

    '/%s/module/tour/day/save' => [
        'controller' => 'Admin:TourDay@saveAction'
    ],

    '/%s/module/tour/day/delete/(:var)' => [
        'controller' => 'Admin:TourDay@deleteAction'
    ],

    // Tour destinations
    '/%s/module/tour/destination' => [
        'controller' => 'Admin:TourDestination@indexAction'
    ],

    '/%s/module/tour/destination/add' => [
        'controller' => 'Admin:TourDestination@addAction'
    ],

    '/%s/module/tour/destination/edit/(:var)' => [
        'controller' => 'Admin:TourDestination@editAction'
    ],

    '/%s/module/tour/destination/save' => [
        'controller' => 'Admin:TourDestination@saveAction'
    ],

    '/%s/module/tour/destination/delete/(:var)' => [
        'controller' => 'Admin:TourDestination@deleteAction'
    ],

    // Tour gallery
    '/%s/module/tour/gallery/add/(:var)' => [
        'controller' => 'Admin:TourGallery@addAction'
    ],

    '/%s/module/tour/gallery/edit/(:var)' => [
        'controller' => 'Admin:TourGallery@editAction'
    ],

    '/%s/module/tour/gallery/save' => [
        'controller' => 'Admin:TourGallery@saveAction'
    ],

    '/%s/module/tour/gallery/delete/(:var)' => [
        'controller' => 'Admin:TourGallery@deleteAction'
    ],

    // Tour price policy
    '/%s/module/tour/price-policy/add/(:var)' => [
        'controller' => 'Admin:TourPricePolicy@addAction'
    ],

    '/%s/module/tour/price-policy/edit/(:var)' => [
        'controller' => 'Admin:TourPricePolicy@editAction'
    ],

    '/%s/module/tour/price-policy/delete/(:var)' => [
        'controller' => 'Admin:TourPricePolicy@deleteAction'
    ],

    '/%s/module/tour/price-policy/save' => [
        'controller' => 'Admin:TourPricePolicy@saveAction'
    ],

    // Hotels
    '/%s/module/tour/hotels' => [
        'controller' => 'Admin:Hotel@indexAction'
    ],

    '/%s/module/tour/hotels/save' => [
        'controller' => 'Admin:Hotel@saveAction'
    ],

    '/%s/module/tour/hotels/add' => [
        'controller' => 'Admin:Hotel@addAction'
    ],

    '/%s/module/tour/hotels/edit/(:var)' => [
        'controller' => 'Admin:Hotel@editAction'
    ],

    '/%s/module/tour/hotels/delete/(:var)' => [
        'controller' => 'Admin:Hotel@deleteAction'
    ],

    // Hotel gallery
    '/%s/module/tour/hotel-gallery/add/(:var)' => [
        'controller' => 'Admin:HotelGallery@addAction'
    ],

    '/%s/module/tour/hotel-gallery/edit/(:var)' => [
        'controller' => 'Admin:HotelGallery@editAction'
    ],

    '/%s/module/tour/hotel-gallery/save' => [
        'controller' => 'Admin:HotelGallery@saveAction'
    ],

    '/%s/module/tour/hotel-gallery/delete/(:var)' => [
        'controller' => 'Admin:HotelGallery@deleteAction'
    ]
];