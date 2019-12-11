<?php

namespace services\goods;
use common\components\Service;
use common\models\goods\Style;
use common\models\goods\Attribute;



/**
 * Class GoodsService
 * @package services\common
 */
class GoodsService extends Service
{    
    
    /**
     * 创建商品列表
     * @param unknown $style_id
     */
    public function createGoods($style_id){
        echo "<pre/>";
        $styleModel = Style::find()->where(['id'=>$style_id])->one();
        $this->buildStyleAttrs($styleModel);exit;
        $style_attr = json_decode($styleModel->style_attr,true);        
        $style_spec = json_decode($styleModel->style_spec,true);
        
        if(!empty($style_attr)){
            $style_attr_ids = array_keys($style_attr);
        }
        if(!empty($style_spec['a'])){
            $style_spec_ids = array_keys($style_spec['a']);
        }
        $attr_ids = array_keys($style_attr);
        print_r($style_attr);
        print_r($style_spec);
    }
    public function buildStyleAttrs($styleModel)
    {
        $style_attr = json_decode($styleModel->style_attr,true);
        $style_spec = json_decode($styleModel->style_spec,true);
        
        //if(!empty($attr_list)){
         //   $attr_ids = array_keys($attr_list);
            $res = \Yii::$app->services->goodsAttribute->getAttrListByTypeId($styleModel->type_id);
            print_r($res);
       // }
    }
    public function batchCreateGoods($style_id){
        
        $styleModel = Style::find()->where(['id'=>$style_id])->one();
        
        $style_attr = json_decode($styleModel->style_attr);
        $style_spec = json_decode($styleModel->style_spec);
        
        print_r($style_attr);
        print_r($style_spec);
    }

}