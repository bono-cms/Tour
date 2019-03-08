<?php

/**
 * Module configuration container
 */

return array(
    'name' => 'Tour',
    'description' => 'Tour module lets you organize and sell tour packages on your site',
    'menu' => array(
        'name' => 'Tours',
        'icon' => 'fas fa-car-side',
        'items' => array(
            array(
                'route' => 'Tour:Admin:Grid@indexAction',
                'name' => 'View all tours'
            ),
            array(
                'route' => 'Tour:Admin:Tour@addAction',
                'name' => 'Add a tour'
            ),
            array(
                'route' => 'Tour:Admin:Category@addAction',
                'name' => 'Add category'
            ),
            array(
                'route' => 'Tour:Admin:Booking@indexAction',
                'name' => 'Bookings'
            ),
            array(
                'route' => 'Tour:Admin:TourDestination@indexAction',
                'name' => 'Tour destinations'
            )
        )
    )
);