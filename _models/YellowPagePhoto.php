<?php
namespace models;

class YellowPagePhoto extends ActiveRecord
{
    const IMAGE_TYPE_NAME = 'yellowpage';
    const PLACEHOLDER = 'placeholder.jpg';

    public static function tableName()  
    {  
        return 'yellow_page_photo';
    }

    public function getStorage()
    {
        return $this->hasOne(Storage::className(), ['id' => 'storage_id']);
    }
}