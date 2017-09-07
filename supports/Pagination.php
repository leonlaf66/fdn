<?php
namespace common\supports;

class Pagination extends \yii\data\Pagination
{
    public $maxPageCount = 100;

    public function getPageCount()
    {
        $pageCount = parent::getPageCount();
        return $pageCount > $this->maxPageCount ? $this->maxPageCount : $pageCount;
    }
}