<?php

namespace services\goods;

use common\models\goods\RingRelation;
use Yii;
use common\components\Service;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\models\goods\Style;


/**
 * Class TypeService
 * @package services\common
 * @author jianyan74 <751393839@qq.com>
 */
class StyleService extends Service
{

    public function getStyle($id,$language = null){
        if(empty($language)){
            $language = Yii::$app->language;
        }
        $model = Style::find()->alias('a')
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['a.id'=>$id])
            ->leftJoin('{{%goods_style_lang}} b',  'b.master_id = a.id and b.language = "'.$language.'"')
            ->select(['a.*', 'b.style_name'])
            ->asArray()
            ->one();
        return $model;
    }


    public function getStyleIdsByRing($ring_id){
        $model = RingRelation::find()
            ->where(['ring_id'=>$ring_id])
            ->select(['style_id'])
            ->asArray()
            ->all();
        return array_column($model,'style_id');
    }
}