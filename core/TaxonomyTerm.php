<?php
namespace common\core;

class TaxonomyTerm extends \yii\db\ActiveRecord
{
    CONST YELLOW_PAGE_TYPE = '2';
    CONST NEWS_TYPE = '3';
    
	public static function tableName()  
    {  
        return 'taxonomy_term';
    }

    public static function typeOptions($tid)
    {
        $taxonomies = self::find()
            ->where([
                'taxonomy_id' => $tid,
                'parent_id' => 0
            ])
            ->asArray()
            ->all();

        return \common\helper\ArrayHelper::index($taxonomies, 'id', 'name');
    }
}