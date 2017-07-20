<?php
return [
    'name' => [
        'title'=>'Name',
        'index'=>false,
        'value'=>function ($val, $rets) {
            $cityName = \common\catalog\Town::getMapValue($rets->town, 'name');
            return \WS::isChinese() ? $cityName.$rets->propTypeName() : $rets->propTypeName().' in '.$cityName;
        }
    ],
    'prop_type_name'=>[
        'title'=>'Property Type',
        'index'=>'prop_type',
        'values'=>[
            'RN'=>'Rental', 
            'SF'=>'Single Family', 
            'MF'=>'Multi Family', 
            'CC'=>'Condominium',
            'CI'=>'Commercial', 
            'BU'=>'Business Opportunity', 
            'LD'=>'Land'
        ]
    ],
    'location'=>[
        'index'=>false,
        'value'=>function($val, $rets) {
            return \common\estate\helpers\Rets::buildLocation($rets);
        }
    ],
    'area'=>[
        'title'=>'Area',
        'value'=>function($value) {
            return \common\estate\helpers\Rets::fetchNameFromDict('area', $value);
        }
    ],
    'rent_fee_includes'=>[
        'title'=>'Rent Includes'
    ],
    'appliances'=>[
        'map'=>true
    ],
    'list_price'=>[
        'title'=>'Price',
        'index'=>'list_price',
        'type'=>'float',
        'prefix'=>'$',
        'format'=>function($value) {
            return number_format($value, 0);
        }
    ],
    'county'=>[
        'title'=>'County',
        'value'=>function($value){
            return \common\estate\helpers\Rets::fetchNameFromDict('counties', $value);
        }
    ],
    'city_name'=>[
        'title'=>'City',
        'index'=>'town',
        'value'=>function($value){
            return \common\catalog\Town::getMapValue($value, 'name');
        }
    ],
    'no_rooms'=>[
        'title'=>'Rooms',
        'type'=>'int'
    ],
    'no_bedrooms'=>[
        'title'=>'Bed Rooms',
        'type'=>'int',
        'default'=>0
    ],
    'no_full_baths'=>[
        'title'=>'Full Baths',
        'type'=>'int',
        'default'=>0
    ],
    'no_half_baths'=>[
        'title'=>'Half Baths',
        'type'=>'int',
        'default'=>0
    ],
    'rooms_descriptions'=>[
        'title'=>'',
        'value'=>function($value, $rets) {
            $bd = intval($rets->no_bedrooms);
            $ba = intval($rets->no_rooms) + intval($rets->no_half_baths) / 2.0;
            return "{$bd}bd {$ba}ba";
        }
    ],
    'lot_size'=>[
        'title'=>'Lot Size',
        'suffix'=>'Sq.Ft',
        'type'=>'float'
    ],
    'square_feet'=>[
        'title'=>'Living Area',
        'type'=>'float',
        'suffix'=>'Sq.Ft'
    ],
    'master_bath'=>[
        'title'=>'Master Bath',
        'map'=>1
    ],
    'list_date'=>[
        'value'=>function($value){
            return date('Y-m-d', strtotime($value));
        }
    ],
    'status'=>[
        'title'=>'Status',
        'value'=>function($value, $rets) {
            return \common\estate\helpers\Rets::toStatusName($value);
        }
    ],
    'status_name'=>[
        'title'=>'Status',
        'value'=>function($value, $rets) {
            return \common\estate\helpers\Rets::toStatusName($value);
        }
    ],
    'basement'=>[
        'values'=>['Y'=>'Yes', 'N'=>'No']
    ],
    'market_time_property'=>[
        'value'=>function ($value, $d) {
            return \common\estate\helpers\Rets::buildMarketDays($d);
        }
    ],
    'no_units'=>[
        'title'=>'Units',
        'type'=>'int'
    ],
    'orig_price'=>[
        'type'=>'float',
        'format'=>function($value){
            return number_format($value, 0);
        }
    ],
    'assessments'=>[
        'type'=>'float',
        'format'=>function($value){
            return number_format($value, 0);
        }
    ],
    'taxes'=>[
        'type'=>'float',
        'format'=>function($value){
            return number_format($value, 0);
        }
    ],
    'list_price_per_sqft'=>[
        'type'=>'float',
        'format'=>function($value){
            return number_format($value, 0);
        }
    ],
    'longitude'=>[
        'value'=>function($value){
            return floatval($value);
        }
    ],
    'latitude'=>[
        'value'=>function($value) {
            return floatval($value);
        }
    ],
    'no_list_days' => [
        'value'=>function($value, $rets) {
            return intval((time() - strtotime($rets->list_date)) / 86400);;
        },
        'suffix'=>'Days',
    ]
];