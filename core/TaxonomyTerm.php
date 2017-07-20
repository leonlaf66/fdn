<?php
namespace common\core;

class TaxonomyTerm extends \yii\db\ActiveRecord
{
	public static function tableName()  
    {  
        return 'catalog_taxonomy_term';
    }
}