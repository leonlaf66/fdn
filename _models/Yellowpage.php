<?php
namespace models;

use yii\data\ActiveDataProvider;

class YellowPage extends ActiveRecord
{
    public $photo;

    public static function tableName()
    {
        return 'yellow_page';
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
        return $this->hasOne(YellowpageCity::className(), ['yellowpage_id' => 'id']);
    }

    public static function hit($id)
    {
        return \WS::$app->db->createCommand()
            ->update('yellow_page', [
                'hits' =>new \yii\db\Expression('hits+1')
            ], 'id=:id', [
                ':id' => $id
            ])->execute();
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

    public static function getOrderItems($key=null)
    {
        $list = \WS::$app->getModule('yellowpage')->getConfigs('yellowpage.order.items');
        if(is_null($key)) return $list;

        return isset($list[$key]) ? $list[$key] : null;
    }

    public function afterFind()
    {
        if(\WS::$app->language == 'zh-CN' && strlen($this->business_cn) > 0) {
            $this->business = $this->business_cn;
        }
        return parent::afterFind();
    }
}