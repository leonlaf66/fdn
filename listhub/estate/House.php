<?php
namespace common\listhub\estate;

class House extends \models\listhub\HouseIndex
{
    protected $_cacheData = [];
    public $groupedSchoolNames = [];

    public function getCache($key, $defValue = null)
    {
        return $this->_cacheData[$key] ?? $defValue;
    }

    public function setCache($key, $data)
    {
        return $this->_cacheData[$key] = $data;

        return $this;
    }

    public function title()
    {
        $cityName = $this->getXmlElement()->one('Address/City')->val();
        if ($city = $this->city) {
            $cityName = tt($this->city->name, $this->city->name_cn);
        }
        $propTypeName = $this->propTypeName();

        $list = [];
        if (\WS::$app->language === 'zh-CN') {
            $list[] = $cityName.$propTypeName;
            $list[] = intval($this->no_bedrooms).'室'.($this->no_bathrooms).'卫';

            if (intval($this->parking_spaces) > 0) {
                $list[] = '带车位';
            }
            return implode(' ', $list);
        }

        $list[] = $cityName.' '.strtolower($propTypeName);
        $list[] = intval($this->no_bedrooms).' bed '.$this->no_bathrooms.' bath';

        return implode(', ', $list);
    }

    public function metaTitle()
    {
        $cityName = '纽约';//\models\Town::getMapValue($this->town, 'name');
        $propTypeName = $this->propTypeName();

        $cnNums = ['一', '两', '三', '四', '五', '六', '七', '八', '九', '十'];

        $list = [];
        if (\WS::$app->language === 'zh-CN') {
            $list[] = $cityName.$propTypeName;
            $cnBadrooms = intval($this->no_bedrooms);
            if ($cnBadrooms > 1 && $cnBadrooms < 10) {
                $cnBadrooms = $cnNums[$cnBadrooms - 1];
            }
            $cnBath = $this->no_bathrooms;
            if ($cnBath > 1 && $cnBath < 10) {
                $cnBath = $cnNums[$cnBath - 1];
            }

            $list[] = $cnBadrooms.'室'.$cnBath.'卫 '.intval($this->no_bedrooms).'室'.$this->no_bathrooms.'卫';

            if (intval($this->parking_spaces) > 0) {
                $list[] = '带车位';
            }
            return implode(' ', $list);
        }

        $list[] = $cityName.' '.strtolower($propTypeName);

        $bedroom = intval($this->no_bedrooms).' '.(intval($this->no_bedrooms) > 1 ? 'bedrooms' : 'bedroom');
        $bath = $this->no_bathrooms;
        $bath = $bath.' '.($bath > 1 ? 'baths' : 'bath');

        $list[] = $bedroom.' '.$bath;

        if (intval($this->parking_spaces) > 0) {
            $list[] = 'has parking';
        }

        return implode(', ', $list);
    }

    public function getPhoto($idx = 0)
    {
        $photos = $this->getPhotos();
        return $photos[$idx] ?? [
            'url' => \WS::$app->params['media']['baseUrl'].'/rets/placeholder_'.strtolower($this->prop_type).'.jpg'
            ];
    }

    public function getPhotos($callback = null)
    {
        $photos = (array)$this->getXmlElement()->xpath('Photos/Photo');

        $photos = array_map(function ($photo) {
            return [
                'url' => (string)$photo->MediaURL
            ];
        }, $photos);

        return $callback ? $callback($photos) : $photos;
    }

    public function isLiked()
    {
        return \WS::$app->db->createCommand('select id from house_member_favority where area_id=:area_id and list_no=:id and user_id=:uid', [
            ':area_id' => \WS::$app->area->id,
            ':id' => $this->id,
            ':uid' => \WS::$app->user->id
        ])->queryScalar();
    }

    public function propTypeName()
    {
        $propTypeNames = \common\listhub\estate\References::getPropTypeNames();
        if (isset($propTypeNames[$this->prop_type])) {
            return tt($propTypeNames[$this->prop_type]);
        }
        return tt('Unknown', '未知');
    }

    public function getListDaysDescription()
    {
        if (!$this->list_date) {
            return false;
        }

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

    public function statusName()
    {
        if ($this->ant_sold_date) {
            return tt('Sold', '已销售');
        }
        return tt('Active', '新房源');
    }

    public function getTags()
    {
        $tagNames = [];

        // 卧室
        if (intval($this->no_bedrooms) >= 3) {
            $tagNames[] = tt('More bedrooms', '卧室充足');
        }

        // 车位
        if (intval($this->parking_spaces) >= 2) {
            $tagNames[] = tt('More parkings', '车位充足');
        }

        // 高级豪宅
        if (in_array($this->prop_type, ['CC', 'SF']) && intval($this->list_price) > 1000000) {
            $tagNames[] = tt('Luxury house', '高级豪宅');
        }

        return $tagNames;
    }

    public function getFieldData($name, $opts = [])
    {
        static $cache = null;
        if (is_null($cache)) {
            $cache = include(__DIR__.'/etc/fields.php');
        }

        if (!is_array($opts)) $opts = [];
        if (array_key_exists($name, $cache)) {
            $opts = array_merge($cache[$name], $opts);
        }
        $opts = array_merge(['emptyDisplayValue' => tt('Unknown', '未提供')], $opts);

        $data = [
            'id' => $name,
            'title' => '',
            'value' => null,
        ];

        if (isset($opts['render']) && is_object($opts['render']) && get_class($opts['render']) === 'Closure') { // 函数渲染
            $data['value'] = ($opts['render'])($this);
        } else { // 非函数渲染
            if (isset($opts['path'])) { // path渲染
                $data['value'] = $this->getXmlElement()->xpath($opts['path']);
                if (isset($opts['filter'])) {
                    if (is_object($opts['filter']) && get_class($opts['filter']) === 'Closure') {
                        $data['value'] = ($opts['filter'])($data['value'], $this);
                    } else {
                        $data['value'] = FieldRender::filter($data['value'], $opts['filter']);
                    }
                } else {
                    $data['value'] = isset($data['value'][0]) ? $data['value'][0]->val() : null;
                }
            } else { // 直接字段
                $data['value'] = $this->$name;
            }
        }

        $data['rawValue'] = $data['value'];

        if (isset($opts['format'])) {
            FieldRender::format($data, $opts['format'], $this);
        }

        // 其它选项渲染
        foreach (['title', 'prefix', 'suffix'] as $optId) {
            if (isset($opts[$optId])) {
                if (is_object($opts[$optId]) && get_class($opts[$optId]) === 'Closure') {
                    $data[$optId] = ($opts[$optId])($this);
                } else {
                    $data[$optId] = $opts[$optId];
                }
            }
        }

        // 构造formatedValue
        if (empty($data['value'])) {
            unset($data['prefix']);
            unset($data['suffix']);
            $data['value'] = $data['formatedValue'] = $opts['emptyDisplayValue'];
        } else {
            if (isset($opts['lang'])) {
                $langs = \common\listhub\estate\References::getLangs($opts['lang']);
                if (isset($langs[$data['value']]) && $langs[$data['value']] !== '') {
                    $data['value'] = $langs[$data['value']];
                }
            }

            $formatedVals = [];
            foreach (['prefix', 'value', 'suffix'] as $key) {
                if (isset($data[$key]) && is_string($data[$key])) $formatedVals[] = $data[$key];
            }
            $data['formatedValue'] = implode('', $formatedVals);
        }

        return $data;
    }

    public function getUrl()
    {
        $type = $this->prop_type === 'RN' ? 'lease' : 'purchase';
        return "{$type}/{$this->id}/";
    }

    public function getGroupedSchoolNames()
    {
        if (empty($this->groupedSchoolNames)) {
            $this->groupedSchoolNames = ['Elementary' => [], 'Middle' => [], 'High' => []];
            $schools = $this->getXmlElement()->xpath('Location/Community/Schools/School');

            foreach ($schools as $school) {
                $catName = $school->one('SchoolCategory')->val();
                if ($catName === 'Primary') $catName = 'Elementary';

                if (in_array($catName, ['Elementary', 'Middle', 'High'])) {
                    if ($name = $school->one('Name')->val()) {
                        $this->groupedSchoolNames[$catName][] = $name;
                    }
                }
            }
        }
        return $this->groupedSchoolNames;
    }

    public function getDetail()
    {
        $typeId = strtolower($this->prop_type);
        $xmlContent = (new \yii\db\Query())
            ->select('xml_rules')
            ->from('listhub_house_field_prop_rule')
            ->where(['type_id'=>$typeId])
            ->scalar();

        $arrGroups = [];

        $xml = simplexml_load_string("<groups>{$xmlContent}</groups>");
        $groups = $xml->xpath('/groups/group');
        foreach($groups as $group) {
            $arrGroup = [
                'title' => (string)$group->title,
                'items' => []
            ];

            if(isset($group->layout)) $arrGroup['layout'] = (string)($group->layout);

            $items = $group->xpath('items');
            $fieldCount = 0;
            foreach($items[0] as $item) {
                $name = $item->getName();
                $opts = (array)$item;

                if(isset($opts['values'])) {
                    $opts['values'] = (array)$opts['values'];
                }

                if (isset($opts['zh-CN'])) {
                    $opts = array_merge($opts, (array)$opts['zh-CN']);
                    unset($opts['zh-CN']);
                }

                $data = $this->getFieldData($name, $opts);
                if (!empty($data['rawValue'])) {
                    $arrGroup['items'][$name] = $this->getFieldData($name, $opts);
                    $fieldCount ++;
                }
            }

            if ($fieldCount > 0)
                $arrGroups[] = $arrGroup;
        }

        return $arrGroups;
    }

    public function recommends($stateId, $limit = 8)
    {
        $cityId = $this->city_id;
        $price = $this->list_price;
        $propTypeId = $this->prop_type; //SF/CC归为一类

        $query = static::find()
            ->addSelect(['*', "abs(list_price - {$price}) as diff_price"])
            ->where(['state' => $stateId])
            ->andWhere(['city_id'=>$cityId, 'prop_type'=>$propTypeId])
            ->andWhere(['>', 'square_feet', 0])
            ->orderBy(['diff_price' => 'ASC']);

        if (in_array($propTypeId, ['SF', 'CC'])) {
            $query->andWhere(['in', 'prop_type', ['SF', 'CC']]);
        } else {
            $query->andWhere(['prop_type'=>$propTypeId]);
        }
        $query->andWhere(['<>', 'id', $this->id]);
        $query->limit($limit);

        $result = $query->all();

        return $result;
    }

    public function getPolygons()
    {
        $ponlygons = [];
        if ($this->city_id) {
            if ($city = \models\City::findOne($this->city_id)) {
                $cityName = $city->name;
                $cityName = strtolower($cityName);
                if (strpos($cityName, ' ') !== false) {
                    $cityName = str_replace(' ', '-', $cityName);
                }
                $ponlygons = \WS::getStaticData('polygons/'.$this->state.'/'.$cityName, []);
            }
        }
        return $ponlygons;
    }

    public function getViewableEntity()
    {
        $obj = new \stdClass();
        $obj->list_no = $this->id;
        $obj->title = $this->title();
        $obj->photo_url = $this->getPhoto(0)['url'];
        $obj->list_price = $this->getFieldData('list_price');
        $obj->status_name = $this->statusName();
        return $obj;
    }

    public static function search($stateId)
    {
        $model = new static();

        $query = $model->find();
        $query->andWhere(['=', 'state', $stateId]);

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15
            ],
            'sort' => [
                'defaultOrder' => [
                    'index_at' => SORT_DESC,
                    'id' => SORT_DESC
                ]
            ],
        ]);

        return $dataProvider;
    }

    public function getCity()
    {
        return $this->hasOne(\models\City::className(), ['id' => 'city_id']);
    }
}