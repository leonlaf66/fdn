<?php
namespace models;

use yii\data\ActiveDataProvider;

class News extends ActiveRecord
{
    public $arrayFields = ['towns'];

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