<?php
return [
    'title' => [
        'render' => function ($d) {
            return $d->title();
        }
    ],
    'meta_title' => [
        'render' => function ($d) {
            return $d->metaTitle();
        }
    ],
    'photo_url' => [
        'render' => function ($d) {
            return $d->photo()['url'];
        }
    ],
    'photo_urls' => [
        'render' => function ($d) {
            return $d->getPhotos(function ($photo) {
                return $photo['url'];
            });
        }
    ],
    'list_days_description' => [
        'render' => function ($d) {
            return $d->getListDaysDescription();
        }
    ],
    'status_name' => [
        'render' => function ($d) {
            return $d->statusName();
        }
    ],
    'tags' => [
        'render' => function ($d) {
            return $d->getTags();
        }
    ],
    'type_name' => [
        'render' => function ($d) {
            return $d->propTypeName();
        }
    ],
    'no_bedrooms' => [
        'title' => tt('Bedrooms', '卧室数')
    ],
    'no_bathrooms' => [
        'title' => tt('Bathrooms', '浴室数')
    ],
    'no_full_baths' => [
        'title' => tt('Full Bathrooms', '全卫'),
        'path' => 'FullBathrooms'
    ],
    'no_half_baths' => [
        'title' => tt('Half Bathrooms', '半卫'),
        'path' => 'HalfBathrooms'
    ],
    'square_feet' => [
        'title' => tt('Square Feet', '面积'),
        'format' => 'sq.ft'
    ],
    'list_price' => [
        'title' => tt('Price', '价格'),
        'format' => 'money'
    ],
    'prop_type_name' => [
        'title' => tt('Property', '类型'),
        'render' => function ($d) {
            $propTypeNames = \common\listhub\estate\Setting::get('references', 'propTypes', []);
            return $propTypeNames[$d->prop_type] ? tt($propTypeNames[$d->prop_type]) : null;
        }
    ],
    'no_list_days' => [
        'title' => tt('List Days', '上市天数'),
        'render' => function ($d) {
            if (!$d->list_date) return null;

            return intval((time() - strtotime($d->list_date)) / 86400);
        },
        'emptyDisplayValue' => null
    ]
];