<?php
return [
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