<?php
namespace common\estate;

use WS;

class Rets extends \models\MlsRets
{
    public static function propertyTypeNames()
    {
        $t = WS::lang('rets', true);
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

    public function title()
    {
        $cityName = \models\Town::getMapValue($this->town, 'name');
        $propTypeName = $this->propTypeName();

        $list = [];
        if (\WS::$app->language === 'zh-CN') {
            $list[] = $cityName.$propTypeName;
            if (!empty($this->no_bedrooms)) {
                $list[] = intval($this->no_bedrooms).'室'.($this->no_full_baths + $this->no_half_baths).'卫';
            }

            if (intval($this->garage_spaces) > 0) {
                $list[] = '带车库';
            } elseif (intval($this->parking_spaces) > 0) {
                $list[] = '带车位';
            }
            return implode(' ', $list);
        }

        $list[] = $cityName.' '.strtolower($propTypeName);
        $list[] = intval($this->no_bedrooms).' bed '.($this->no_full_baths + $this->no_half_baths).' bath';

        return implode(', ', $list);
    }

    public function metaTitle()
    {
        $cityName = \models\Town::getMapValue($this->town, 'name');
        $propTypeName = $this->propTypeName();

        $cnNums = ['一', '两', '三', '四', '五', '六', '七', '八', '九', '十'];

        $list = [];
        if (\WS::$app->language === 'zh-CN') {
            $list[] = $cityName.$propTypeName;
            $cnBadrooms = intval($this->no_bedrooms);
            if ($cnBadrooms > 1 && $cnBadrooms < 10) {
                $cnBadrooms = $cnNums[$cnBadrooms - 1];
            }
            $cnBath = $this->no_full_baths + $this->no_half_baths;
            if ($cnBath > 1 && $cnBath < 10) {
                $cnBath = $cnNums[$cnBath - 1];
            }

            $list[] = $cnBadrooms.'室'.$cnBath.'卫 '.intval($this->no_bedrooms).'室'.($this->no_full_baths + $this->no_half_baths).'卫';

            if (intval($this->garage_spaces) > 0) {
                $list[] = '带车库';
            } elseif (intval($this->parking_spaces) > 0) {
                $list[] = '带车位';
            }
            return implode(' ', $list);
        }

        $list[] = $cityName.' '.strtolower($propTypeName);

        $bedroom = intval($this->no_bedrooms).' '.(intval($this->no_bedrooms) > 1 ? 'bedrooms' : 'bedroom');
        $bath = $this->no_full_baths + $this->no_half_baths;
        $bath = $bath.' '.($bath > 1 ? 'baths' : 'bath');

        $list[] = $bedroom.' '.$bath;

        if (intval($this->garage_spaces) > 0) {
            $list[] = 'has garage';
        } elseif (intval($this->parking_spaces) > 0) {
            $list[] = 'has parking';
        }
        return implode(', ', $list);
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
        if (\WS::$app->language === 'zh-CN') {
            return $days === 0 ? '当日上市' : '已上市'.$days.'天';
        }
        if ($days === 0) {
            return 'New listing';
        }
        if ($days === 1) {
            return $days.' day on market';
        }
        return $days.' days on market';
    }

    public function getTagsCode()
    {
        $tags = '00000';

        // 学区房
        $areaCodes = \models\SchoolDistrict::allCodes();
        if (in_array($this->town, $areaCodes)) {
            $tags[0] = '1';
        }

        // 卧室
        if (intval($this->no_bedrooms) >= 3) {
            $tags[1] = '1';
        }

        // 车位
        if (intval($this->parking_spaces) >= 2) {
            $tags[2] = '1';
        }

        // 车库
        if (intval($this->garage_spaces) > 0) {
            $tags[3] = '1';
        }

        // 高级豪宅
        if (in_array($this->prop_type, ['CC', 'SF']) && intval($this->list_price) > 1000000) {
            $tags[4] = '1';
        }

        return $tags;
    }

    public function getTags()
    {
        $tagNames = [];
        
        // 学区房
        $areaCodes = \models\SchoolDistrict::allCodes();
        if (in_array($this->town, $areaCodes)) {
            $tagNames[] = tt('School district', '学区房');
        }

        // 卧室
        if (intval($this->no_bedrooms) >= 3) {
            $tagNames[] = tt('More bedrooms', '卧室充足');
        }

        // 车位
        if (intval($this->parking_spaces) >= 2) {
            $tagNames[] = tt('More parkings', '车位充足');
        }

        // 车库
        if (intval($this->garage_spaces) > 0) {
            $tagNames[] = tt('Has garage', '带车库');
        }
        
        // 高级豪宅
        if (in_array($this->prop_type, ['CC', 'SF']) && intval($this->list_price) > 1000000) {
            $tagNames[] = tt('Luxury house', '高级豪宅');
        }

        return $tagNames;
    }

    public static function all($listNos)
    {
        return self::find()->where(['in', 'list_no', $listNos])->all();
    }

    public function getUrl()
    {
        $type = $this->prop_type === 'RN' ? 'lease' : 'purchase';
        return "{$type}/{$this->list_no}/";
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
            return \WS::$app->params['media']['baseUrl'].'/rets/placeholder_'.strtolower($this->prop_type).'.jpg';
        }
        return \common\estate\helpers\Rets::getPhotoUrl($this->list_no, $n, $w, $h);
    }

    public function getLocation()
    {
        return \common\estate\helpers\Rets::buildLocation($this);
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

    public function isLiked()
    {
        return WS::$app->db->createCommand('select id from house_member_favority where area_id=:area_id and list_no=:id and user_id=:uid', [
            ':area_id' => 'ma',
            ':id' => $this->list_no,
            ':uid' => WS::$app->user->id
        ])->queryScalar();
    }

    public function getTypeInstance()
    {
        $instanceClassName = 'common\\estate\\rets\\'.$this->prop_type;
        return new $instanceClassName($this);
    }

    public function getViewableEntity()
    {
        $obj = new \stdClass();
        $obj->list_no = $this->list_no;
        $obj->title = $this->title();
        $obj->photo_url = $this->getPhoto(0, 300, 300);
        $obj->list_price = $this->render()->get('list_price');
        $obj->status_name = $this->statusName();
        return $obj;
    }

    public function toArray(array $fields = [], array $expand = [], $recursive = true)
    {
        $data = parent::toArray($fields, $expand, $recursive);
        $data = array_merge($data, (array)$this->json);
        return $data;
    }
}