<?php
namespace models;

class University extends \yii\base\Object
{
    CONST IMAGE_TYPE = 'universities';

    public $title;
    public $location;
    public $image_url;
    public $radius;
    public $areas;
    public $search_type = 'location';

    public static function findAll()
    {
        $rows = self::loadData();

        foreach($rows as $idx=>$row) {
            $rows[$idx] = new self($row);
        }

        return $rows;
    }

    public function getImage()
    {
        return \common\helper\Media::init(self::IMAGE_TYPE)
            ->getObject($this->image_url);
    }

    public static function loadData()
    {
        $data = include(__DIR__.'/university/data.php');
        return $data['universities'];
    }
}