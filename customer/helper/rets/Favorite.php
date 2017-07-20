<?php
namespace common\customer\helper\rets;

use WS;
use common\customer\RetsFavorite;

class Favorite
{
    public static function all($retsList)
    {
        $userId = WS::$app->user->id;

        $listNoArr = [];
        foreach($retsList as $rets) {
            $listNoArr[] = $rets->list_no;
        }

        $fineds = RetsFavorite::find()->where(['list_no'=>$listNoArr])->andWhere(['user_id'=>$userId])->all();
        $founedListNos = [];
        foreach($fineds as $m) {
            $founedListNos[] = $m->list_no;
        }

        return $founedListNos;
    }
}
