<?php

namespace services\common;

use common\models\common\Express;
use common\models\common\ExpressLang;
use Yii;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\components\Service;


/**
 * Class MemberService
 * @package services\backend
 * @author jianyan74 <751393839@qq.com>
 */
class ExpressService extends Service
{

    public function findOne($id, $language = null)
    {
        if($language == null) {
            $language = \Yii::$app->params['language'];
        }
        return Express::find()->alias('m')
            ->where(['m.id'=>$id])
            ->leftJoin(ExpressLang::tableName().' lang','lang.master_id = m.id and lang.language =  "'.$language.'"')
            ->select(['lang.express_name as name','m.*'])
            ->asArray()
            ->one();
    }
    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findAll($language = null)
    {
        if($language == null) {
            $language = \Yii::$app->params['language'];
        }
        return Express::find()->alias('m')
            ->where(['status' => StatusEnum::ENABLED])
            ->leftJoin(ExpressLang::tableName().' lang','lang.master_id = m.id and lang.language =  "'.$language.'"')
            ->select(['lang.express_name as name','m.*'])
            ->orderBy('sort asc')
            ->asArray()
            ->all();
    }

    /**
     * 下拉框
     * @param unknown $language
     * @return array
     */
    public function getDropDown($language = null){
        $models = $this->findAll($language);
        return ArrayHelper::map($models, 'id', 'name');

    }




}