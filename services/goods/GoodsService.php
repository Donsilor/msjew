<?php

namespace services\goods;
use common\components\Service;
use common\models\goods\Style;
use common\enums\InputTypeEnum;
use common\models\goods\Goods;
use common\helpers\ArrayHelper;
use common\models\goods\GoodsLang;


/**
 * Class GoodsService
 * @package services\common
 */
class GoodsService extends Service
{    
    
    /**
     * 创建商品列表
     * @param int $style_id
     * @param Goods $goodsModel
     */
    public function createGoods($style_id){
        
        $styleModel = Style::find()->where(['id'=>$style_id])->one();
        $spec_array = json_decode($styleModel->style_spec,true);
        if(!empty($spec_array['c'])){
            $goods_list = $spec_array['c'];
            $specb_list = $spec_array['b'];
        }else{
            $goods_list = [
                 [
                     'goods_sn' =>$styleModel->style_sn,
                     'sale_price' =>$styleModel->sale_price,
                     'cost_price' =>$styleModel->cost_price,
                     'market_price' =>$styleModel->market_price,
                     'goods_storage' =>$styleModel->goods_storage,
                     'status' =>$styleModel->status,  
                 ]                
            ];
        }
        $default_data = $this->formatStyleAttrs($styleModel);
        //商品更新
        foreach ($goods_list as $key=>$goods){
            //禁用没有填写商品编号的，过滤掉
            if(empty($goods['goods_sn']) && empty($goods['status'])){
                continue;
            }
            $goodsModel = Goods::find()->where(['style_id'=>$style_id,'goods_sn'=>$goods['goods_sn']])->one();
            if(!$goodsModel || empty($goods['goods_sn'])) {
                //新增
                $goodsModel = new Goods();
            }
            $goodsModel->style_id = $styleModel->id;//款式ID
            $goodsModel->type_id  = $styleModel->type_id;//产品线ID
            $goodsModel->goods_image  = $styleModel->style_cover;//商品默认图片
            $goodsModel->goods_sn = $goods['goods_sn'];//商品编码            
            $goodsModel->sale_price = $goods['sale_price']??0;//销售价 
            $goodsModel->market_price = $goods['market_price']??0; //成本价
            $goodsModel->cost_price = $goods['cost_price']??0;//成本价
            $goodsModel->goods_storage = $goods['goods_storage']??0;//库存
            $goodsModel->status = $goods['status']??0;//上下架状态 
            $goodsModel->spec_key = $key;
            /* 
             * 备用
             * if(!empty($specb_list[$key]['ids'])){
                $spec_ids = explode(",",$specb_list[$key]['ids']);
                $spec_vids = explode(",",$specb_list[$key]['vids']);
                $goods_spec = array_combine($spec_ids, $spec_vids);
                $goodsModel->goods_spec = json_encode($goods_spec);
            } */
            if(!empty($default_data['style_spec_b'][$key])){
                $goods_specs = $default_data['style_spec_b'][$key];
                $goodsModel->goods_spec = json_encode($goods_specs['spec_keys']);
            }
            
            $goodsModel->save(false);  
           
            //商品多语言保存更新 goods_lang
            $languages = \Yii::$app->params['languages']??[];
            foreach ($languages as $lang_key=>$lang_name){
                if($lang_key == \Yii::$app->language){
                    $format_data = $default_data;
                }else{
                    $format_data = $this->formatStyleAttrs($styleModel,$lang_key);
                }
                $spec_list = $format_data['style_spec_b']??[];
                $langModel = GoodsLang::find()->where(['master_id'=>$goodsModel->id,'language'=>$lang_key])->one();
                if(!$langModel) {
                    //新增
                    $langModel = new GoodsLang();
                    $langModel->master_id = $goodsModel->id;
                    $langModel->language  = $lang_key;
                }
                $goods_spec = $format_data['style_spec_b'][$key]??[];
                $langModel->goods_spec = !empty($goods_spec)?json_encode($goods_spec) : null;
                $langModel->save(false);
            }
        }

    }
    /*
     * 
     */
    public function formatStyleAttrs($styleModel,$language = null)
    {
        $type_id = $styleModel->type_id;
        $style_attr = json_decode($styleModel->style_attr,true);
        $style_spec = json_decode($styleModel->style_spec,true);

        if(!empty($style_attr)) {
            $spec_array['style_attr'] = $style_attr;
        }
        if(!empty($style_spec['a'])) {
            $spec_array['style_spec_a'] = $style_spec['a'];
        }
        if(!empty($style_spec['b'])) {
            $spec_array['style_spec_b'] = $style_spec['b'];
        }
        if(!empty($style_spec['c'])) {
            $spec_array['style_spec_c'] = $style_spec['c'];
        }
        $format_data = [];
        foreach ($spec_array as $key =>$spec){
            if($key == 'style_spec_b' || $key == 'style_spec_c'){
                $format_data[$key] = $spec;
                continue;
            }else {
                $attr_ids = array_keys($spec);
                $attr_list = \Yii::$app->services->goodsAttribute->getSpecAttrList($attr_ids,$type_id,1,$language);
                foreach ($attr_list as $attr){
                    $attr_id = $attr['id'];
                    $is_text = InputTypeEnum::isText($attr['input_type']);
                    $is_single = InputTypeEnum::isSingle($attr['input_type']);
                    $attr['is_text'] = $is_text;
                    $attr['is_single'] = $is_single;
                    $attr['value_id'] = 0;
                    $attr['value'] = $spec[$attr_id];
                    $attr['all'] = [];
                    if(!$is_text){
                        $attr['value_id'] = $spec[$attr_id];//属性值ID列表
                        $attr['value'] = \Yii::$app->services->goodsAttribute->getValuesByValueIds($attr['value_id'],$language);
                        $attr['all'] = \Yii::$app->services->goodsAttribute->getValuesByAttrId($attr_id,1,$language);
                    }
                    $format_data[$key][$attr['id']] = $attr;
                }
            }

        }
        $style_spec_a = $format_data['style_spec_a'] ??[];
        $style_spec_b = $spec_array['style_spec_b'] ??[];
        if(!empty($style_spec_a)) {
            //处理style_spec_b
            $attr_map = array_column($style_spec_a,'attr_name','id');
            $value_map  = array_column($style_spec_a,'all','id');
            $value_map = ArrayHelper::multiToArray($value_map);
            foreach ($style_spec_b as $key=>$spec){
                $attr_ids = explode(',',$spec['ids']);
                $value_ids = explode(',',$spec['vids']);
                $spec_name = [];
                $spec_value = [];
                foreach ($attr_ids as $attr_id){                
                    $spec_name[$attr_id] = $attr_map[$attr_id]??'';
                }                
                foreach ($value_ids as $k=>$value_id){
                    $spec_value[$value_id] = $value_map[$value_id]??'';
                }
                $spec_keys = array_combine($attr_ids,$value_ids);
                $spec_names = array_combine($spec_name, $spec_value);
                $format_data['style_spec_b'][$key] = [
                       'spec_name'=>$spec_name,
                       'spec_value'=>$spec_value,
                       'spec_keys'=> $spec_keys,
                       'spec_names'=> $spec_names,
                ];
            }
        }
        return $format_data;
    }   
   

}