<?php
return [
    /*lease*/
    'lease.home.deluxe.hot.ids' => [
        'type' => 'text',
        'private' => true
    ],
    'lease.home.deluxe.hot.more' => [
        'type' => 'text',
        'private' => true
    ],
    'lease.home.newest.hot.ids' => [
        'type' => 'text',
        'private' => true
    ],
    'lease.home.newest.hot.more' => [
        'type' => 'text',
        'private' => true
    ],
    //purchase
    'purchase.home.deluxe.hot.ids' => [
        'type' => 'text',
        'private' => true
    ],
    'purchase.home.deluxe.hot.more' => [
        'type' => 'text',
        'private' => true
    ],
    'purchase.home.newest.hot.ids' => [
        'type' => 'text',
        'private' => true
    ],
    'purchase.home.newest.hot.more' => [
        'type' => 'text',
        'private' => true
    ],
    'purchase.mortgage-calculator.interest-rate.default' => [
        'type' => 'text',
        'private' => true,
        'default' => 4.5
    ],
    /*google map*/
    'google.map.key' => [
        'type' => 'text'
    ],
    /*luxury*/
    'home.luxury.houses' => [
        'type' => 'HomeLuxuryHouse',
        'default' => [],
        'private' => true
    ],
];