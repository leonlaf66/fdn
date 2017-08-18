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
            'name'=>tt('Name', '姓名'),
            'phone_number'=>tt('Phone Number', '联系电话'),
            'job_name'=>tt('Job', '职业'),
            'where_from'=>tt('Where are you from?', '您来自哪里？')
        ];
    }

    public static function whereFromOptions()
    {
        return [
            'cn'=>tt('Chinese', '中国'),
            'us'=>tt('United States', '美国')
        ];
    }
}