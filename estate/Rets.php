<?php
namespace common\estate;

use WS;

class Rets extends \common\core\ActiveRecord
{
    public $json = [];

    public static function tableName()
    {
        return 'mls_rets';
    }

    public static function primaryKey()
    {
        return ['list_no'];
    }

    public function rules()
    {
        return [
            [['json'], 'safe']
        ];
    }

    public static function getDb()
    {
        return WS::$app->mlsdb;
    }

    public static function propertyTypeNames()
    {
        $t = WS::lang('@rets', true);
        return [
            'RN'=>$t('Rental'), 
            'SF'=>$t('Single Family'), 
            'MF'=>$t('Multi Family'), 
            'CC'=>$t('Condominium'),
            'CI'=>$t('Commercial'), 
            'BU'=>$t('Business Opportunity'), 
            'LD'=>$t('Land')
        ];
    }

    public static function propertyTypes()
    {
        return self::propertyTypeNames();
    }

    public static function propertyTypeOptions()
    {
        return [
            'RN'=>1, 
            'SF'=>2, 
            'MF'=>3, 
            'CC'=>4,
            'CI'=>5, 
            'BU'=>6, 
            'LD'=>7
        ];
    }

    public function propId()
    {
        $ids = self::propertyTypeOptions();
        return $ids[$this->prop_type] ?? null;
    }

    public function statusName()
    {
        $status = $this->status;

        if($status === 'NEW') {
            return \WS::$app->language === 'zh-CN' ? '新房源' : 'New';
        }

        if (in_array($status, ['ACT', 'BOM', 'PCG', 'RAC', 'EXT'])) {
            return \WS::$app->language === 'zh-CN' ? '销售中' : 'Active';
        }

        return \WS::$app->language === 'zh-CN' ? '已销售' : 'Sold';
    }

    public function propTypeName()
    {
        $types = self::propertyTypeNames();
        return $types[$this->prop_type] ?? '未知';
    }

    public function getListDaysDescription()
    {
        $days = intval((time() - strtotime($this->list_date)) / 86400);
        return $days === 0 ? '当日上市' : '已上市'.$days.'天';
    }

    public function getTags()
    {
        $tagNames = [];
        
        // 学区房
        $areaCodes = \common\catalog\SchoolDistrict::allCodes();
        if (in_array($this->town, $areaCodes)) {
            $tagNames[] = '学区房';
        }

        // 卧室
        if (intval($this->no_bedrooms) >= 3) {
            $tagNames[] = '卧室充足';
        }

        // 车位
        if (intval($this->parking_spaces) >= 2) {
            $tagNames[] = '车位充足';
        }

        // 车库
        if (intval($this->garage_spaces) > 0) {
            $tagNames[] = '带车库';
        }
        
        // 高级豪宅
        if (in_array($this->prop_type, ['CC', 'SF']) && intval($this->list_price) > 1000000) {
            $tagNames[] = '高级豪宅';
        }

        return $tagNames;
    }

    public static function all($listNos)
    {
        return self::find()->where(['in', 'list_no', $listNos])->all();
    }

    public function render()
    {
        static $renders = [];

        $listNo = $this->list_no;
        if (!isset($renders[$listNo])) {
            $renders[$listNo] = new \common\estate\helpers\RetsRender($this);
        }
        
        return $renders[$listNo];
    }

    public function __get($name)
    {
        $value = null;
        try {
            $value = parent::__get($name);
        } catch (\Exception $e) {
            return $this->getJsonData($name);
        }
        return $value;
    }

    public function getPhotos($w = 300, $h = 300)
    {
        $urls = [];
        for ($n=0; $n < $this->photo_count; $n++) {
            $urls[] = $this->getPhoto($n, $w, $h);
        }
        return $urls;
    }

    public function getPhoto($n = 0, $w=300, $h = 300)
    {
        if ($n > $this->photo_count - 1) {
            return \WS::$app->params['rets']['defPhotoUrl'];
        }
        return \common\estate\helpers\Rets::getPhotoUrl($this->list_no, $n, $w, $h);
    }

    public function getLocation()
    {
        return \common\estate\helpers\Rets::buildLocation($this);
    }

    public function afterFind()
    {
        parent::afterFind();

        $this->json = json_decode($this->json_data);
        unset($this->json_data);
    }

    public function getJsonData($name, $def = null)
    {
        return isset($this->json->$name) ? $this->json->$name : $def;
    }

    public function getTownPolygons()
    {
        $cityName = $this->render()->get('city')['value'];
        $cityName = strtolower($cityName);
        if(strpos($cityName, ' ') !== false) {
            $cityName = str_replace(' ', '-', $cityName);
        }

        $polygonFile = \common\estate\helpers\Rets::getConfigFile('map.city.polygon/'.$cityName.'.php');
        if(file_exists($polygonFile)) {
            return include($polygonFile);
        }
        
        return [];
    }

    public static function cityOptions()
    {
        return include(__DIR__.'/config/dicts/cities.php');
    }

    public function getTypeInstance()
    {
        $instanceClassName = 'common\\estate\\rets\\'.$this->prop_type;
        return new $instanceClassName($this);
    }

    public function toArray(array $fields = [], array $expand = [], $recursive = true)
    {
        $data = parent::toArray($fields, $expand, $recursive);
        $data = array_merge($data, (array)$this->json);
        return $data;
    }
}