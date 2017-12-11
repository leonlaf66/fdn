<?php
namespace models;

class YellowPageType extends ActiveRecord
{
    public static function tableName()
    {
        return 'yellow_page_type';
    }

    public function getTerm()
    {
        return $this->hasOne(TaxonomyTerm::className(), ['id' => 'type_id']);
    }

    public function getName()
    {
        if($term = $this->term) {
            return $this->term->name;
        }
        return '';
    }

    public static function termMap()
    {
        $terms = TaxonomyTerm::find()->where(['taxonomy_id'=>2, 'status'=>0])->orderBy('parent_id', 'ASC')->all();

        $map = [];
        foreach ($terms as $term) {
            if($term->parent_id ==0 ) {
                $map[$term->id] = $term->name;
            }
            elseif(isset($map[$term->parent_id])) {
                $map[$term->id] = $map[$term->parent_id].' => '.$term->name;
                //unset($map[$term->parent_id]);
            }
        }

        return $map;    
    }
}