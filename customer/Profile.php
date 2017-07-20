<?php
namespace common\customer;

use WS;

class Profile extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'user_profile';
    }

    public static function primaryKey()
    {
        return ['user_id'];
    }

    public function rules()
    {
        return [
            [['name', 'phone_number', 'job_name', 'where_from'], 'required'],
            [['name'], 'string', 'max'=>20],
            [['phone_number'], 'string', 'max' => 30],
            [['job_name'], 'string', 'max' => 30],
            [['where_from'], 'in', 'range' => ['cn', 'us']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name'=>\WS::t('profile', 'Name'),
            'phone_number'=>\WS::t('profile', 'Phone'),
            'job_name'=>\WS::t('profile', 'Job'),
            'where_from'=>\WS::t('profile', 'Where are you from?')
        ];
    }

    public static function whereFromOptions()
    {
        return [
            'cn'=>\WS::t('@profile', 'Chinese'),
            'us'=>\WS::t('@profile', 'United States')
        ];
    }
}