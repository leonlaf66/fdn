<?php
namespace common\catalog;

class Zipcode extends \common\core\ActiveRecord
{
    public static function tableName()  
    {  
        return 'zipcode_town';
    }

    public static function searchKeywords($words, $stateId = 'MA')
    {
        return self::find()->where([
            'state' => $stateId
        ])->andWhere([
            'zip' => $words,
        ])->one();
    }
}