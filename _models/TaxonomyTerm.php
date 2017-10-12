<?php
namespace models;

class TaxonomyTerm extends ActiveRecord
{
    const YELLOW_PAGE = 2;

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

    public static function getTreeItems($type, $parentId = 0)
    {
        $terms = self::find()
            ->orderBy(['parent_id'=>SORT_ASC, 'sort_order'=>SORT_ASC])
            ->where('taxonomy_id=:id and status=0 and parent_id=:pid', [
                ':id'=>$type,
                ':pid' => $parentId
            ])
            ->all();

        $parents = [];
        $data = array();
        foreach($terms as $m) {
            $data[] = $m->attributes;
        }
        
        return \module\core\helpers\DataFormatHelper::toTree($data, 'id', 'name', 'parent_id');
    }
}