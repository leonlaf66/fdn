<?php
namespace models;

class HouseRoi extends ActiveRecord
{
    public static function tableName()
    {
        return 'house_info_roi';
    }

    public static function primaryKey()
    {
        return ['LIST_NO'];
    }

    public function attributeLabels()
    {
        return [
            'EST_ROI_MORTGAGE'=>\WS::t('rets-roi', 'Westimated ROI (With 20% Down)'),
            'EST_ANNUAL_INCOME_MORTGAGE'=>\WS::t('rets-roi', 'Net Income (With 20% Down)'),
            'EST_ROI_CASH'=>\WS::t('rets-roi', 'Westimated ROI (Cash)'),
            'EST_ANNUAL_INCOME_CASH'=>\WS::t('rets-roi', 'Net Income (Cash)'),
            'APPX_COST'=>\WS::t('rets-roi', 'Appx. Cost (Exclude Insurance)')
        ];
    }

    public function formats()
    {
        return [
            'EST_ROI_MORTGAGE'=>function($value){
                return number_format(floatval($value) * 100,2).' %';
            },
            'EST_ANNUAL_INCOME_MORTGAGE'=>function($value) {
                return '$ '.number_format(floatval($value), 2);
            },
            'EST_ROI_CASH'=>function($value) {
                return number_format(floatval($value) * 100,2).' %';
            },
            'EST_ANNUAL_INCOME_CASH'=>function($value) {
                return '$ '.number_format(floatval($value), 2);
            },
            'APPX_COST'=>function($value) {
                return '$ '.number_format(floatval($value), 2);
            }
        ];
    }
}