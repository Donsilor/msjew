<?php

namespace services\goods;
use common\components\Service;
use common\models\goods\Style;
use common\enums\InputTypeEnum;
use common\models\goods\Goods;
use common\helpers\ArrayHelper;
use common\models\goods\GoodsLang;
use common\enums\StatusEnum;
use common\models\goods\AttributeIndex;
use common\enums\AttrTypeEnum;
use common\models\goods\StyleLang;
use common\models\goods\Diamond;
use common\models\goods\DiamondLang;
use common\enums\DiamondEnum;
use function GuzzleHttp\json_encode;


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
        $default_data = $this->formatStyleAttrs($styleModel,true);
        //款式商品属性索引表 更新入库
        $attr_index_list = $default_data['attr_index']??[];
        AttributeIndex::deleteAll(['style_id'=>$styleModel->id]);
        foreach ($attr_index_list as $attributes){
              $model = new AttributeIndex();
              $attributes['style_id'] = $styleModel->id;
              $attributes['type_id'] = $styleModel->type_id;
              $model->attributes = $attributes;
              $model->save(false);
        }
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
            $goodsModel->goods_image  = $styleModel->style_image;//商品默认图片
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
                    $format_data = $this->formatStyleAttrs($styleModel,false,$lang_key);
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
    /**
    * 款式属性格式化
    * @param Style $styleModel  款式model实例
    * @param string $is_attrindex 是否属性索引 
    * @param string $language 语言
    * @return array[]|string[][]|unknown[][]|\common\helpers\unknown[][]|unknown[]
    */
    public function formatStyleAttrs($styleModel, $is_attrindex= false , $language= null)
    {
        $type_id = $styleModel->type_id;
        $style_attr = json_decode($styleModel->style_attr,true);
        $style_spec = json_decode($styleModel->style_spec,true);
        $spec_array = array();
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
                    //$attr['is_text'] = $is_text;
                    //$attr['is_single'] = $is_single;
                    $attr['value_id'] = 0;
                    $attr['value'] = $spec[$attr_id];
                    $attr['all'] = [];
                    if(!$is_text){
                        $attr['value_id'] = $spec[$attr_id];//属性值ID列表
                        $attr['value'] = \Yii::$app->services->goodsAttribute->getValuesByValueIds($attr['value_id'],$language);
                        $attr['all'] = \Yii::$app->services->goodsAttribute->getValuesByAttrId($attr_id,StatusEnum::ENABLED,$language);
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
        if($is_attrindex == true) {
            //属性索引
            $format_data['attr_index'] = $this->formatGoodsAttrIndex($format_data);
        }
        return $format_data;
    } 
    /**
     * 款式属性索引格式化
     * @param array $data
     */
    public function formatGoodsAttrIndex($data)
    {
        $index_list = [];
        if(!empty($data['style_attr']) && is_array($data['style_attr'])) {
            foreach ($data['style_attr'] as $attr){
                $attr_list = [];
                if(is_array($attr['value'])){
                    foreach ($attr['value'] as $val_id=>$val_name){
                        $index_list[] = [
                                'attr_name'=>$attr['attr_name'],
                                'attr_id' =>$attr['id'],
                                'attr_type'=>$attr['attr_type'],
                                'attr_value_id'=>$val_id,
                                'attr_value'=> null,
                        ];
                    }                    
                }else if(trim($attr['value']) != ''){
                    $index_list[] = [
                            'attr_name'=>$attr['attr_name'],
                            'attr_id' =>$attr['id'],
                            'attr_type'=>$attr['attr_type'],
                            'attr_value_id'=>$attr['value_id'],
                            'attr_value'=>$attr['value'],
                    ];
                }
            }
            
        }
        
        if(!empty($data['style_spec_b']) && is_array($data['style_spec_b'])) {
            foreach ($data['style_spec_b'] as $key=>$attr){
                $goods = $data['style_spec_c'][$key];
                if(empty($goods['status']) || empty($goods['goods_sn'])){
                    continue;
                }
                if(is_array($attr['spec_keys'])){
                    foreach ($attr['spec_keys'] as $attr_id=>$val_id){
                        $index_list['spec_'.$attr_id.'_'.$val_id] = [
                                'attr_name'=>$attr['spec_name'][$attr_id],
                                'attr_id' =>$attr_id,
                                'attr_type'=>AttrTypeEnum::TYPE_SALE,
                                'attr_value_id'=>$val_id,
                                'attr_value'=> null,
                        ];
                    }                    
                }
            }
            
        }
        
        return $index_list;
    }
    /**
     * 查询商品详情
     * @param unknown $goods_id
     * @param number $goods_type
     */
    public function getGoodsInfo($goods_id , $goods_type = 0, $format_attr = true, $language= null)
    {
        if(!$language) {
            $language = \Yii::$app->params['language'];
        }
        //如果是裸钻
        if($goods_type == \Yii::$app->params['goodsType.diamond']) {
            $goods = Diamond::find()->alias('g')
                        ->where(['goods_id'=>$goods_id])
                        ->innerJoin(DiamondLang::tableName().' lang','g.id=lang.master_id')
                        ->select(['g.*','g.goods_sn as style_sn','lang.goods_name','lang.goods_body','g.goods_num as goods_storage'])->asArray()->one();
             $goods_attr = [
                    DiamondEnum::CARAT=>$goods['carat'],
                    DiamondEnum::COLOR=>$goods['color'],
                    DiamondEnum::CLARITY=>$goods['clarity'],
                    DiamondEnum::CUT=>$goods['cut']                                
            ];
            $goods['goods_attr'] = json_encode($goods_attr); 
            $goods['goods_spec'] = null;
            
        }else {
            $query = Goods::find()->alias('g')
                    ->innerJoin(Style::tableName()." s","g.style_id=s.id")
                    ->innerJoin(StyleLang::tableName()." sl","s.id=sl.master_id and sl.language='{$language}'")
                    ->select(['g.*','s.style_sn','sl.style_name as goods_name','sl.goods_body','s.style_attr as goods_attr'])
                    ->where(['g.id'=>$goods_id]);
            
            $goods = $query->asArray()->one();
            
       }  
       
       if($format_attr == true) { 
           
           $goods['lang'] = [
                 'goods_attr' => $this->formatGoodsAttr($goods['goods_attr'],$goods['type_id'],$language),
                 'goods_spec' => $this->formatGoodsSpec($goods['goods_spec'], $language)
           ];
       }
       return $goods;
    }
    /**
     * 格式化商品属性
     * @param array $goods_attr
     * @param array $goods_type
     * @param string $language
     * @return array
     */
    public function formatGoodsAttr($goods_attr, $goods_type, $language = null) 
    {
        if(!$language) {
            $language = \Yii::$app->params['language'];
        }
        
        if(!is_array($goods_attr)) {
            $goods_attr = json_decode($goods_attr,true);
        }
        if(!is_array($goods_attr)) {
            return [];
        }
        $attr_ids = array_keys($goods_attr);
        $attr_list = \Yii::$app->services->goodsAttribute->getSpecAttrList($attr_ids,$goods_type,StatusEnum::ENABLED,$language);
        
        $attr_data = [];
        foreach ($attr_list as $attr){
            $attr_id = $attr['id'];
            $value = $goods_attr[$attr_id];
            if($value == ""){
                continue;
            }
            $is_text = InputTypeEnum::isText($attr['input_type']);
            $is_single = InputTypeEnum::isSingle($attr['input_type']);            
            if(!$is_text){
                if(is_array($value)) {
                    foreach ($value as $value_id) {
                        $attr['value'][$value_id] = \Yii::$app->attr->valueName($value_id ,$language);
                    }
                }else {
                    $attr['value'][$value] = \Yii::$app->attr->valueName($value ,$language);
                }
            } else {
                $attr['value'] = [$attr_id=>$value];
            }
            $attr_data[$attr_id] = $attr;
        }
        return $attr_data;  
    }
    /**
     * 获取商品规格属性
     * @param unknown $goods_spec
     * @param unknown $language
     * @return unknown[][]|mixed[][]|boolean[][]|string[][]
     */
    public function formatGoodsSpec($goods_spec, $language = null)
    {
        if(!is_array($goods_spec)) {
            $goods_spec = json_decode($goods_spec,true);
        }
        if(!is_array($goods_spec)) {
            return [];
        }
        $spec_data = [];
        foreach ($goods_spec as $attr_id=>$value_id){
            $attr_name = \Yii::$app->attr->attrName($attr_id ,$language);
            $value_name = \Yii::$app->attr->valueName($value_id ,$language);
            $spec_data[] = [
                    'attr_id'=>$attr_id,
                    'value_id'=>$value_id,
                    'attr_name'=>$attr_name,
                    'attr_value'=>$value_name,
            ];
        }
        return $spec_data;
    }
    /**
     * 规格属性合并
     * @param unknown $goods_attr
     * @param unknown $goods_spec
     * @param unknown $goods_type
     * @param unknown $language
     */
    public function formatGoodsAttrAndSpec($goods_attr, $goods_spec, $goods_type, $language = null) 
    {
        $data = array();
        $goods_attr = $this->formatGoodsAttr($goods_attr, $goods_type,$language);
        foreach ($goods_attr as $vo) {
            $data['goods_attr'][$vo['id']] = [
                'attr_id' =>$vo['id'],
                'value_id'=>implode("/", array_keys($vo['value'])),
                'attr_name'=>$vo['attr_name'],
                'attr_value'=>implode("/", $vo['value'])                    
            ];
        }
        $data['goods_spec'] = $this->formatGoodsSpec($goods_spec,$language);
        return $data;
    }
    
    /**
     * 格式化商品属性数据
     * @param unknown $goods
     * @param unknown $language
     * @return unknown
     */
    public function formatGoodsAttr222($goods, $language = null)
    {
        if(!$language) {
            $language = \Yii::$app->params['language'];
        }
        if(empty($goods)) {
           return false;
        }
        $goods_attr = $goods['goods_attr']??[];
        $goods_type = $goods['type_id'] ?? ($goods['goods_type'] ?? 0);        
        
        if(!is_array($goods_attr)) {
            $goods_attr = json_decode($goods_attr,true);
        }        
        
        $attr_ids = array_keys($goods_attr);        
        $attr_list = \Yii::$app->services->goodsAttribute->getSpecAttrList($attr_ids,$goods_type,StatusEnum::ENABLED,$language);
        
        $attr_data = [];
        foreach ($attr_list as $attr){
            $attr_id = $attr['id'];
            $value = $goods_attr[$attr_id];
            if($value == ""){
                continue;
            }
            $is_text = InputTypeEnum::isText($attr['input_type']);
            $is_single = InputTypeEnum::isSingle($attr['input_type']);
            
            if(!$is_text){
                if(is_array($value)) {
                    foreach ($value as $value_id) {
                        $attr['value'][$value_id] = \Yii::$app->attr->valueName($value_id ,$language);
                    }
                }else {
                    $attr['value'][$value] = \Yii::$app->attr->valueName($value ,$language);
                }
            } else {
                $attr['value'] = [$attr_id=>$value];
            }
            $attr_data[$attr_id] = $attr;
        }
        $goods['goods_attr'] = $attr_data;  
                
        $goods_spec = $goods['goods_spec']??[];
        if(!is_array($goods_spec)) {
            $goods_spec = json_decode($goods_spec,true);
        }  
        //print_r($goods);exit;
        $spec_data = [];
        foreach ($goods_spec as $attr_id=>$value_id){
            $attr_name = \Yii::$app->attr->attrName($attr_id ,$language);
            $value_name = \Yii::$app->attr->valueName($value_id ,$language);            
            $spec_data[] = [
                    'attr_id'=>$attr_id,
                    'value_id'=>$value_id,
                    'attr_name'=>$attr_name,
                    'attr_value'=>$value_name,
            ];
        }         
        $goods['goods_spec'] = $spec_data;
        //print_r($goods);exit;
        return $goods;
    }







    //获取款和对应的商品
    public function formatStyleGoodsById($style_id, $language=null){
        if(empty($language)){
            $language = \Yii::$app->params['language'];
        }
        $style_spec_array = [
            '10' =>array(
                 'attr_name'=>'materials',
                 'key_name'=>'material',
             ),//成色
             '38'=>array(
                 'attr_name'=>'sizes',
                 'key_name'=>'size',
             ),  // 尺寸
        ];
        $query = Style::find()->alias('m')
            ->leftJoin(StyleLang::tableName().' lang',"m.id=lang.master_id and lang.language='".$language."'")
            ->where(['m.id'=>$style_id]);
        $style_model =  $query->one();
        $format_style_attrs = $this->formatStyleAttrs($style_model);
//        return $format_style_attrs;
        $model = $query ->select(['m.id','m.style_sn','m.status','m.goods_images','m.type_id','m.style_3ds','m.style_image','sale_price','lang.goods_body','lang.style_name','lang.meta_title','lang.meta_word','lang.meta_desc'])
            ->asArray()->one();

        //规格属性
        $style = array();
        $style['id'] = $model['id'];
        $style['goodsName'] = $model['style_name'];
        $style['goodsCode'] = $model['style_sn'];
        $style['goodsImages'] = $model['goods_images'];
        $style['salePrice'] = $model['sale_price'];
        $style['coinType'] = $this->getCurrencySign();
        $style['goods3ds'] = $model['style_3ds'];
        $style['goodsDesc'] = $model['goods_body'];
        $style['categoryId'] = $model['type_id'];
        $style['goodsGiaImage'] = null;
        $style['goodsMod'] = $model['type_id'] == 12 ? 1: 2;
        $style['goodsStatus'] = $model['status']== 1? 2:1;
        $style['htmlUrl'] = null;
        $style['metaDesc'] = $model['meta_desc'];
        $style['metaTitle'] = $model['meta_title'];
        $style['metaWord'] = $model['meta_word'];
        $style['qrCode'] = '';
        $style['recommends'] = null;
        $style['templateId'] = null;

        if(isset($format_style_attrs['style_spec_a'])){
            $format_style_spec = $format_style_attrs['style_spec_a'];
            foreach ($style_spec_array as $key => $val){
                if(isset($format_style_spec[$key]['value'])){
                    foreach ($format_style_spec[$key]['value'] as $k => $v){
                        $attr = array();
                        $attr['id'] = $k;
                        $attr['name'] = $v;
                        $style[$val['attr_name']][] = $attr;
                    }
                }else{
                    $style[$val['attr_name']] = null;
                }
            }
        }

        //基础属性
        if(isset($format_style_attrs['style_attr'])){
            $style_attr = $format_style_attrs['style_attr'];
            foreach ($style_attr as $attr){
                //对售後服務特殊处理
                if($attr['id'] == 52){
                    $style['goodsServices'] = join(',', $attr['value_id']);
                    $style['goodsServicesJsons'] = \Yii::$app->services->goodsAttribute->getAttrValuesByValueIds($attr['value_id']);
                    continue;
                }

                $attr_value = $attr['value'];
                $attr_value_id = $attr['value_id'];
                if(empty($attr_value)) {
                    continue;
                }
                if(is_array($attr_value)){
                    $attr_value = implode('|',$attr_value);
                }
                if(is_array($attr_value_id)){
                    $attr_value_id = implode('|',$attr_value_id);
                }
                $style['specs'][] = [
                    'categoryId'=>$attr['attr_type'],
                    'configAttrId'=>$attr_value_id,
                    'configId'=>$attr['id'],
                    //'configInputType'=>$attr['input_type'],
                    'configName'=>$attr['attr_name'],
                    'configAttrVal'=>$attr_value,
                    'goodsId'=>$style_id,
                   // 'configRequired'=>$attr['is_require'],
                   //'queryColumn'=>null,
                   //'id'=>'',
                   //'sort'=>null
                ];
            }
        }

        //商品
        $goods_array = Goods::find()
            ->where(['style_id'=>$style_id ,'status'=>StatusEnum::ENABLED])
            ->select(['id','type_id','goods_sn','sale_price','goods_storage','warehouse','goods_spec'])
            ->asArray()
            ->all();
        $details = array();
        foreach ($goods_array  as $key => $val){
            $goods_spec = json_decode($val['goods_spec']);
            $goods_spec = (array)$goods_spec;
            foreach ($style_spec_array as $k => $v){
                if(isset($goods_spec[$k])){
                    $details[$key][$v['key_name']] = (int)$goods_spec[$k];
                }else{
                    $details[$key][$v['key_name'] ]= null;
                }
            }


//            if(!empty($goods_spec)){
//                foreach ($goods_spec as $k=>$v){
//                    $details[$key][$style_spec_array[$k]['key_name']] = (int)$v;
//                }
//            }

            $details[$key]['barCode'] = null;
            $details[$key]['productNumber'] = null;
            $details[$key]['stock'] = $val['goods_storage'];;
            $details[$key]['warehouse'] = $val['warehouse'];;
            $details[$key]['categoryId'] = $model['type_id'];
            $details[$key]['goodsDetailsCode'] = $val['goods_sn'];
            $details[$key]['retailMallPrice'] = $val['sale_price'];
            $details[$key]['retailPrice'] = null;
            $details[$key]['goodsId'] = $style_id;
            $details[$key]['id'] = $val['id'];
            $details[$key]['categoryId'] = $model['type_id'];

        }

        $style['details'] = $details;

        return $style;

    }







}