<?php
namespace common\yellowpage;

use module\core\helpers\Image;

class YellowPageCities extends \yii\db\ActiveRecord 
{  
    public static function tableName()  
    {  
        return 'catalog_yellow_page_cities';
    }
}