<?php
return [
    'propTypes' => [
        'RN' => ['Rental', '租房'],
        'MF' => ['Multi family', '多家庭'],
        'SF' => ['Single family', '单家庭'],
        'CC' => ['Condominium', '公寓'],
        'CI' => ['Commercial', '商业房'],
        'LD' => ['Land', '土地'],
    ],
    'propTypesMap' => [
        'RN' => function ($propType, $propSubType) {
            return $propType === 'Rental';
        },
        'MF' => function ($propType, $propSubType) {
            return $propType === 'MultiFamily';
        },
        'SF' => function ($propType, $propSubType) {
            return in_array($propSubType, ['Single Family Attached', 'Single Family Detached']);
        },
        'CC' => function ($propType, $propSubType) {
            return in_array($propSubType, ['Condominium', 'Apartment']);
        },
        'CI' => function ($propType, $propSubType) {
            return $propType === 'Commercial';
        },
        'LD' => function ($propType, $propSubType) {
            return $propType === 'Lots And Land';
        }
    ],
];
