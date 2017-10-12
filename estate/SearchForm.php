<?php
namespace common\rets;

use WS;
use yii\helpers\ArrayHelper as Ah;

use yii\data\ActiveDataProvider;

class SearchForm extends \yii\base\Model
{
    public $is_renal = false; //是否出租
    public $q; //keywords
    public $prop_type; //房屋类型
    public $city; //城市区域
    public $sdistrict; //学区
    public $subway; //地铁线
    public $s_station; //地铁站
    public $price_range = []; //价格范围
    public $square_feet_rang = []; //面积范围
    public $beds; //卧室数
    public $baths; //浴室数
    public $parking; //车位数
    public $market_days; //上市天数

    public function setIsRenal($flag)
    {
        $this->is_renal = $flag;

        return $this;
    }

    public function rules()
    {
        return [
            [['q'], 'string', 'min'=>2],
            [['city', 'sdistrict', 'subway', 's_station', 
                'price_range', 'square_feet_rang', 
                'beds', 'baths', 'parking', 'market_days'], 'number'],
            [['prop_type'], 'string', 'length'=>2]
        ];
    }

    public static function search($filters = [])
    {
        $query = \common\estate\HouseIndex::find();

        //首先需规定是租房还是售房
        $query->andWhere([
            'is_renal'=>$this->is_renal
        ]);

        //需包装为数据提供者
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);

        //将参数装进来
        $this->load($filters);

        if (! $this->validate()) {
            return $dataProvider;
        }

        //获取搜索配置
        $searchConfig = $this->_getSearchConfig();

        //构造并执行需要应用的filters
        $andFilters = [];
        foreach($searchConfig['filters'] as $field=>$filterOptions) {
            if ($applyer = Ah::getValue($filterOptions, 'apply')) {
                $applyer($this->$field, $query);
            } else {
                $applyIndex = Ah::getValue($filterOptions, 'applyIndex', $field);
                $andFilters[$applyIndex] = $this->$field;
            }
        }
        $query->andFilterWhere($andFilters);

        //返回
        return $dataProvider;
    }

    protected function _getSearchConfig()
    {
        $propertyRange = $this->is_renal ? 'renal' : 'sell';

        $searchConfigs = [];
        if (!isset($searchConfigs[$propertyRange])) {
            $searchConfigs[$propertyRange] = \yii\helpers\ArrayHelper::marge(
                include($this->_getSearchFile('base')),
                include($this->_getSearchFile($propertyRange))
            );
        }

        return $searchConfigs[$propertyRange];
    }

    protected function _getSearchFile($name)
    {
        return __DIR__.'/config/search/'.$name.'.php';
    }
}