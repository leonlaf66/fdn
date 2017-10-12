<?php
namespace models;

class ZipcodeTown extends ActiveRecord
{
    public static function tableName()
    {
        return 'zipcode_town';
    }

    public static function primaryKey()
    {
        return ['id'];
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