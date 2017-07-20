<?php
return [
    'q'=>[
        'placeholder'=>'搜索关键词',
        'apply'=>function ($q, $query) {
            $q = str_replace("'", '', $q);
            $query->andWhere(['@@', 'location::tsvector', "plainto_tsquery('".$q."')"])
        }
    ],
    'typeFilters'=>[

    ],
    'generalFilters'=>[

    ],
    'dropdownFilters'=>[
        
    ]
    'filters'=>[
        'q'=>[
            'title'=>'搜索关键词',
            'apply'=>function ($q, $query) {
                $q = str_replace("'", '', $q);
                $query->andFilterWhere(['@@', 'location::tsvector', "plainto_tsquery('".$q."')"])
            }
        ],
        'city'=>[
            'title'=>'城市',
            'applyIndex'=>'town'
        ],
        'sdistrict'=>[
            'title'=>'学区',
            'items'=>function () {
                return [];
            },
            'apply'=>function ($value, $query) {

            }
        ],
        'subway'=>[
            'title'=>'地铁',
            'items'=>function () {

            },
            'apply'=>function ($value, $query) {

            }
        ],
        's_station'=>[

        ]
    ],
    'sorts'=>[

    ]
];