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
            // $imageUrl = media_url('news/img/'.$imageUrl);
        }

        return $imageUrl;
    }

    public function afterFind()
    {
        if ($content = $this->content) {
            // 替换内容中的图片
            if (preg_match_all('/<img.*?src="(.*?)".*?>/is', $content, $matchs)) {
                foreach ($matchs[1] as $imageUrl) {
                    if (substr($imageUrl, 0, 2) === '//') {
                        $imageUrl = substr($imageUrl, 2);

                        $newImageUrl = media_url('news/img/'.$imageUrl);
                        $content = str_replace('//'.$imageUrl, $newImageUrl, $content);
                    }
                }
            }
            $this->content = $content;
        }
        return parent::afterFind();
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