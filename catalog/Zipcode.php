<?php
namespace common\catalog;

class Zipcode extends \common\core\ActiveRecord
{
    public static function tableName()  
    {  
        return 'zipcodes';
    }

    public static function searchKeywords($words)
    {
        return self::find()->where([
            'state' => \WS::$app->stateId
        ])->andWhere([
            'zip' => $words,
        ])->one();
    }
}