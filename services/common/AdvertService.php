<?php

namespace services\common;

use common\models\common\Advert;
use common\models\common\AdvertArea;
use common\models\common\AdvertImages;
use common\models\common\AdvertImagesLang;
use PHPUnit\Util\Type;
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
            ->leftJoin('{{%common_advert_lang}} lang','lang.master_id = m.id and lang.language =  "'.$language.'"')
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
            ->leftJoin('{{%common_advert_lang}} lang','lang.master_id = m.id and lang.language =  "'.$language.'"')
            ->select(['lang.adv_name as name','m.*'])
            ->asArray()
            ->all();
    }


    public function getDropDown($language){
        $models = $this->findAll($language);
        return ArrayHelper::map($models, 'id', 'name');

    }


    public function getTypeAdvertImage($type_id,$adv_id,$language){
        $time = date('Y-m-d H:i:s', time());
        $query =  AdvertImages::find()->alias('m')
            ->where([ 'm.status'=>StatusEnum::ENABLED, 'm.adv_id'=>$adv_id])
            ->andWhere(['or',['and',['<=','m.start_time',$time], ['>=','m.end_time',$time]],['m.end_time'=>null]])
            ->leftJoin(AdvertImagesLang::tableName().' lang','lang.master_id = m.id and lang.language =  "'.$language.'"')
            ->select(['lang.title as title','ifnull(lang.adv_image,m.adv_image) as adv_image','adv_url'])
            ->orderby('m.sort desc, m.created_at desc');

        if($type_id == 0){
            // 如果父父级没有，则直接获取位置图片
            $model = $query->asArray()->all();
            return $model;
        }
        $model = $query ->andWhere(['m.type_id'=>$type_id])->asArray()->all();
        if(empty($model)){
            //获取父级生产线图片
            $parent = Type::find()->where(['id'=>$type_id])->asArray()->one();
            $type_id = $parent['pid'];
            return $this->getTypeAdvertImage($type_id, $adv_id, $language);
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
        // 先删除
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