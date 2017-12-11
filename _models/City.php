<?php
namespace models;

class City extends ActiveRecord
{
    public $arrayFields = ['zip_codes'];

    public static function tableName()
    {  
        return 'city';
    }

    public static function findByName($state, $name)
    {
        return self::find()
            ->where(['state' => $state])
            ->andWhere('name=:name or name_cn=:name', [':name' => $name])
            ->orderBy(['type_rule' => SORT_ASC, 'id' => SORT_ASC])
            ->limit(1)
            ->one();
    }

    public static function findByPostalcode($state, $code)
    {
        return self::find()
            ->where(['state' => $state])
            ->andWhere('zip_codes @> :zipcode', [':zipcode' => '{'.$code.'}'])
            ->orderBy(['type_rule' => SORT_ASC, 'id' => SORT_ASC])
            ->limit(1)
            ->one();
    }

    public static function getSearchList($stateId, $filterCallable)
    {
        $query = (new \yii\db\Query())
            ->from('city e')
            ->where(['e.state' => $stateId])
            ->orderBy(['e.type_rule' => SORT_ASC, 'e.id' => SORT_ASC]);

        if (get_class($filterCallable) === 'Closure') {
            $filterCallable($query);
        }

        $rows = $query->all();

        $zipCodeItems = [];

        $items = [];
        foreach ($rows as $row) {
            $name = $row['name'];
            $items[$name] = [
                'title' => $name,
                'desc' => ($row['name_cn'] ?? '暂无中文名').','.$stateId
            ];

            $nameCn = $row['name_cn'];
            if ($nameCn) {
                $items[$nameCn] = [
                    'title' => $nameCn,
                    'desc' => $row['name'].','.$stateId
                ];
            }

            // 建立城市/zipcode映射
            $zipCodes = explode(',', preg_replace('/[\{\}]/', '', $row['zip_codes']));
            foreach ($zipCodes as $zipCode) {
                if (!isset($zipCodeItems[$zipCode])) { // 只取第一个
                    $zipCodeItems[$zipCode] = $row;
                }
            }
        }

        foreach ($zipCodeItems as $zipCode => $row) {
            $zipCode = str_pad($zipCode, 5);
            $items[$zipCode] = [
                'title' => $zipCode,
                'desc' => $row['name'].','.($row['name_cn'] ?? '暂无中文名').','.$stateId
            ];
        }

        return array_values($items);
    }

    public static function mapOptions($stateId, $idField = 'id')
    {
        $citys = self::find()->where(['state'=>$stateId])->all();
        return \common\helper\ArrayHelper::index($citys, $idField, tt('name', 'name_cn'));
    }
}