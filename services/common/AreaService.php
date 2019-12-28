<?php

namespace services\common;

use Yii;
use common\models\common\Area;
use common\components\Service;


/**
 * Class ProvincesService
 * @package services\common
 * @author jianyan74 <751393839@qq.com>
 */
class AreaService extends Service
{
   
    /**
     * 根据id数组获取区域名称
     *
     * @param $id
     * @return mixed
     */
    public function getAreaListName(array $ids,$language = null)
    {
        if($language == null) {
            $language = \Yii::$app->params['language'];
        }
        $name ="name_".strtolower(str_replace('-','_',$language)); 
        return Area::find()->select(['id',$name." as name"])->where(['in', 'id', $ids])->orderBy('id asc')->asArray()->all();
    }
    /**
     * 根据id获取区域
     * @param unknown $id
     * @param unknown $language
     * @return \yii\db\ActiveRecord|array|NULL
     */
    public function getArea($id, $language = null)
    {
        if($language == null) {
            $language = \Yii::$app->params['language'];
        }
        $name ="name_".strtolower(str_replace('-','_',$language));
        $model = Area::find()->select(['id',$name." as name"])->where(['id' => $id])->asArray()->one();
        return $model;
    }
    
}