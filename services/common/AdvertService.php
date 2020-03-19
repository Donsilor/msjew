<?php

namespace services\common;

use common\enums\AreaEnum;
use Yii;
use common\models\common\Advert;
use common\models\common\AdvertArea;
use common\models\common\AdvertImages;
use common\models\common\AdvertImagesLang;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\components\Service;
use common\models\common\AdvertLang;
use common\models\goods\GoodsType;

/**
 * Class MemberService
 * @package services\backend
 * @author jianyan74 <751393839@qq.com>
 */
class AdvertService extends Service
{

    public function findOne($id, $language = null)
    {
        if($language == null) {
            $language = \Yii::$app->params['language'];
        }
        return Advert::find()->alias('m')
            ->where(['m.id'=>$id])
            ->leftJoin(AdvertLang::tableName().' lang','lang.master_id = m.id and lang.language =  "'.$language.'"')
            ->select(['lang.adv_name as name','m.*'])
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
        return Advert::find()->alias('m')
            ->where(['status' => StatusEnum::ENABLED])
            ->leftJoin(AdvertLang::tableName().' lang','lang.master_id = m.id and lang.language =  "'.$language.'"')
            ->select(['lang.adv_name as name','m.*'])
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

    /**
     * 
     * @param unknown $type_id
     * @param unknown $adv_id
     * @param unknown $language
     * @return array|\yii\db\ActiveRecord[]|unknown|array|\yii\db\ActiveRecord[]
     */
    public function getTypeAdvertImage($type_id, $adv_id, $language = null,$area_id=null){
        
        if($language == null) {
            $language = \Yii::$app->params['language'];
        }
        if($area_id == null) {
            $area_id = $this->getAreaId();
        }
        $time = date('Y-m-d H:i:s', time());
        $query =  AdvertImages::find()->alias('m')
            ->select(['lang.title as title','m.adv_image','adv_url'])
            ->leftJoin(AdvertImagesLang::tableName().' lang','lang.master_id = m.id and lang.language =  "'.$language.'"')
            ->where([ 'm.status'=>StatusEnum::ENABLED, 'm.adv_id'=>$adv_id])
            ->andWhere(['like','m.area_ids',$area_id])
            ->andWhere(['or',['and',['<=','m.start_time',$time], ['>=','m.end_time',$time]],['m.end_time'=>null]])
            ->orderby('m.sort asc, m.created_at desc');

        if($type_id == 0){
            // 如果父父级没有，则直接获取位置图片
            $model = $query->asArray()->all();
            //如果没有获取到，则获取大陆的
            if(empty($model) && $area_id != AreaEnum::China){
                $area_id = AreaEnum::China;
                return $this->getTypeAdvertImage($type_id, $adv_id, $language,$area_id);
            }
            return $model;
        }

        $model = $query ->andWhere(['m.type_id'=>$type_id])->asArray()->all();
        if(empty($model)){
            //获取父级生产线图片
            $goodsType = GoodsType::find()->select(['pid'])->where(['id'=>$type_id])->one();
            return $this->getTypeAdvertImage($goodsType->pid, $adv_id, $language);
        }else{
            return $model;
        }
    }



    /**
     * 更新广告位关系表
     * @param unknown $adver_image_id
     */
    public function createAdverArea($adver_image_id){
        
        $adver_image = AdvertImages::find()->where(['id'=>$adver_image_id])->one();
        AdvertArea::deleteAll(['adv_image_id'=>$adver_image_id]);
        if(!$adver_image->area_ids){            
            $adv_area_arr = explode(',',$adver_image->area_ids);
            foreach ($adv_area_arr as $area_id){
                $model = new AdvertArea();
                $model->adv_id = $adver_image->adv_id;
                $model->area_id = $area_id;
                $model->adv_image_id = $adver_image_id;
                $model->save(false);
            }

        }
    }



}