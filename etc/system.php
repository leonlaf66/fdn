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
    /*news*/
    'news.banner.top' => [
        'type' => 'NewsBannerTop',
        'default' => [],
        'private' => true
    ],
    'app.news.banner.top' => [
        'type' => 'AppNewsBanner',
        'default' => [],
        'private' => true
    ],
    /*seo*/
    'friended.links' => [
        'type' => 'textarea'
    ]
];