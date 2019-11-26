<?php

namespace services\backend;

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
class SettingService extends Service
{


    public function findAdvertOne($id, $language)
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
    public function findAll()
    {
        return Member::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();
    }

}