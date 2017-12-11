<?php
namespace models;

use yii\data\ActiveDataProvider;

class News extends ActiveRecord
{
    public $arrayFields = ['area_id'];

    public static function tableName()
    {
        return 'news';
    }

    public function getImageUrl($defaultImageFile = '')
    {
        $content = $this->content;

        $imageUrl = media_url($defaultImageFile);

        if (preg_match('/<img.*?src=[\"|\']?(.*?)[\"|\']?\s.*?>/i', $content, $matchs)) {
            $imageUrl = $matchs[1];
        }

        return $imageUrl;
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($this->status == 1) {
            \WS::$app->shellMessage->send('news-process/index '.$this->id);
            // $this->processImages();
        }
        return parent::afterSave($insert, $changedAttributes);
    }

    public static function query($areaId, $typeId = 0)
    {
        $model = new static();

        $query = $model->find();
        $query->andWhere('(is_public=true or area_id @> :area_id)', [
            ':area_id' => '{'.$areaId.'}'
        ]);
        $query->andWhere(['status' => 1]);
        if ($typeId) {
            $query->andWhere(['type_id' => intval($typeId)]);
        }

        return $query;
    }

    public static function search($areaId, $typeId = 0)
    {
        $query = static::query($areaId, $typeId);
        
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pagesize' => 10
            ],
            'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]]
        ]);
    }
}