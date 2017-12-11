<?php
namespace models;

class ZipcodeCity extends ActiveRecord
{
    public static function tableName()
    {
        return 'zipcode_city';
    }

    public static function primaryKey()
    {
        return ['zip_code'];
    }

    public static function searchKeywords($stateId, $words)
    {
        return self::find()->where([
            'state' => $stateId
        ])->andWhere([
            'zip_code' => $words,
        ])->one();
    }

    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }
}