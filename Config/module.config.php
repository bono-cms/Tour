<?php

/**
 * Module configuration container
 */

return [
    'name' => 'Tour',
    'description' => 'Tour module lets you organize and sell tour packages on your site',
    'menu' => [
        'name' => 'Tours',
        'icon' => 'fas fa-car-side',
        'items' => [
            [
                'route' => 'Tour:Admin:Grid@indexAction',
                'name' => 'View all tours'
            ],
            [
                'route' => 'Tour:Admin:Tour@addAction',
                'name' => 'Add a tour'
            ],
            [
                'route' => 'Tour:Admin:Category@addAction',
                'name' => 'Add category'
            ],
            [
                'route' => 'Tour:Admin:Booking@indexAction',
                'name' => 'Bookings'
            ],
            [
                'route' => 'Tour:Admin:TourDestination@indexAction',
                'name' => 'Tour destinations'
            ]
        ]
    ]
];