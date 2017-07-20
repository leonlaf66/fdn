<?php
namespace common\news;

class News extends \common\core\ActiveRecord
{
    public $arrayFields = ['towns'];

    public static function tableName()
    {
        return 'news';
    }

    public static function types()
    {
        return [
            '1'=>'Immigration',
            '2'=>'House Market',
            '3'=>'Boston News'
        ];
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
}