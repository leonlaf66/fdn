<?php
namespace models;

class ZipcodeRoi extends ActiveRecord
{
    public static function tableName()
    {
        return 'zipcode_roi_ave';
    }

    public static function primaryKey()
    {
        return ['ZIP_CODE'];
    }

    public function attributeLabels()
    {
        return [
            'AVE_ROI_MORTGAGE'=>\WS::t('rets-roi', 'Area ROI (With 20% Down)'),
            'AVE_ANNUAL_INCOME_MORTGAGE'=>\WS::t('rets-roi', 'Area Avg. Net Income (With 20% Down)'),
            'AVE_ROI_CASH'=>\WS::t('rets-roi', 'Area ROI (Cash)'),
            'AVE_ANNUAL_INCOME_CASH'=>\WS::t('rets-roi', 'Area Avg. Net Income (Cash)')
        ];
    }

    public function formats()
    {
        return [
            'AVE_ROI_MORTGAGE'=>function($value){
                return number_format(floatval($value) * 100,2).' %';
            },
            'AVE_ANNUAL_INCOME_MORTGAGE'=>function($value) {
                return '$'.number_format(floatval($value), 2);
            },
            'AVE_ROI_CASH'=>function($value) {
                return number_format(floatval($value) * 100,2).' %';
            },
            'AVE_ANNUAL_INCOME_CASH'=>function($value) {
                return '$ '.number_format(floatval($value), 2);
            }
        ];
    }
}