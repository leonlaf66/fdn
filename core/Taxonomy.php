<?php
namespace common\core;

class Taxonomy extends \yii\db\ActiveRecord
{
	public function tableName()  
    {  
        return 'taxonomy';
    }
}