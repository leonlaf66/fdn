<?php
namespace common\estate;

use WS;
use yii\data\ActiveDataProvider;
use common\helper\ArrayHelper;

class RetsIndex extends \common\core\ActiveRecord
{
    public static function tableName()
    {
        return 'rets_mls_index';
    }

    public static function primaryKey()
    {
        return ['id'];
    }

    public function entity($ids = [])
    {
        if (empty($ids)) {
            if ($data = WS::$app->cache->get('mls:'.$this->id)) {
                return $data;
            }
            $data = Rets::findOne($this->id);
            WS::$app->cache->set('mls:'.$this->id, $data);
            return $data;
        } else {
            return Rets::find()->where(['in', 'list_no', $ids])->all(); 
        }
    }

    public function nearbyHouses($limit=8) {
        $id = $this->id;
        $town = $this->town;
        $price = $this->list_price;
        /* $localLatitude = $this->latitude;
        $localLongitude = $this->longitude;
        */
        $propTypeId = $this->prop_type; //SF/CC归为一类

        /* $query = self::find()
            ->addSelect(['*', "earth_distance(ll_to_earth(latitude, longitude), ll_to_earth({$localLatitude}, {$localLongitude})) as distance"])
            ->where(['town'=>$town, 'prop_type'=>$propTypeId])
            ->orderBy(['distance'=>'ASC']);
        */
        $query = self::find()
            ->addSelect(['*', "abs(list_price - {$price}) as diff_price"])
            ->where(['town'=>$town, 'prop_type'=>$propTypeId])
            ->orderBy(['diff_price' => 'ASC']);

        if (in_array($propTypeId, ['SF', 'CC'])) {
            $query->andWhere(['in', 'prop_type', ['SF', 'CC']]);
        } else {
            $query->andWhere(['prop_type'=>$propTypeId]);
        }
        $query->andWhere(['<>', 'id', $this->id]);
        $query->limit($limit);

        $result = $query->all();
        // \WS::$app->cache->set($cacheKey, $result);

        return $result;
    }

    public static function townTotals()
    {
        $all = (new \yii\db\Query())
            ->select('town, count(*) as total')
            ->from('rets_mls_index')
            ->groupBy('town')
            ->all();

        return ArrayHelper::index($all, 'town', 'total');
    }

    public static function search($filters = [])
    {
        $model = new self();

        $query = $model->find();
        $query->andWhere(['=', 'is_show', true]);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'class' => '\common\supports\Pagination',
                'pagesize' => 15
             ]
        ]);

        if (!($model->load($filters) && $model->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere($filters);

        return $dataProvider;
    }
}
