<?php

namespace services\common;

use common\models\setting\Advert;
use Yii;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\components\Service;

/**
 * Class MemberService
 * @package services\backend
 * @author jianyan74 <751393839@qq.com>
 */
class AdvertService extends Service
{


    public function findOne($id, $language)
    {
        return Advert::find()->alias('m')
            ->where(['m.id'=>$id])
            ->leftJoin('{{%advert_lang}} lang','lang.master_id = m.id and lang.language =  "'.$language.'"')
            ->select(['lang.adv_name as name','m.*'])
            ->asArray()
            ->one();
    }
    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findAll($language)
    {
        return Advert::find()->alias('m')
            ->where(['status' => StatusEnum::ENABLED])
            ->leftJoin('{{%advert_lang}} lang','lang.master_id = m.id and lang.language =  "'.$language.'"')
            ->select(['lang.adv_name as name','m.*'])
            ->asArray()
            ->all();
    }


    public function getDropDown($language){
        $models = $this->findAll($language);
        return ArrayHelper::map(ArrayHelper::itemsMergeDropDown($models,'id','name'), 'id', 'name');

    }

}