<?php
namespace common\yellowpage;

use yii\data\ActiveDataProvider;

class YellowPage extends \yii\db\ActiveRecord
{
    public $photo;
    
    public static function tableName()
    {
        return 'catalog_yellow_page';
    }

    public function getType()
    {
        return $this->hasOne(YellowPageType::className(), ['yellow_page_id' => 'id']);
    }

    public function getTypes()
    {
    	return $this->hasMany(YellowPageType::className(), ['yellow_page_id' => 'id']);
    }

    public function getTypeName()
    {
    	$typeInstance = $this->type;
    	if(! $typeInstance) return '';

    	$termInstance = $typeInstance->term;
    	if(! $termInstance) return '';

    	return $termInstance->name;
    }

    public function getPhotoImageInstance()
    {
        return \common\helper\Media::init('yellowpage')->getImageInstance($this->photo_hash);
    }

    public function getCity()
    {
        return $this->hasOne(YellowPageCities::className(), ['yellowpage_id' => 'id']);
    }

    public static function search()
    {
        $model = new self();
        
        return new ActiveDataProvider([
            'query' => $model->find(),
            'pagination' => [
                'pagesize' => 15
             ]
        ]);
    }
}