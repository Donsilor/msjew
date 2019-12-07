<?php
/**
 * Created by PhpStorm.
 * User: BDD
 * Date: 2019/12/7
 * Time: 13:53
 */

namespace services\goods;


use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\models\goods\DiamondSource;

class DiamondSourceService
{
    public function getDiamondSource(){
        $model = DiamondSource::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->select(['id','name'])
            ->asArray()
            ->all();

        return ArrayHelper::map($model,'id', 'name');
    }


}