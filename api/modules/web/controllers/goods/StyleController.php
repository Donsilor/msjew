<?php

namespace api\modules\web\controllers\goods;

use Yii;
use api\controllers\OnAuthController;
use common\models\goods\Style;
use common\helpers\ResultHelper;
use common\models\goods\StyleLang;
use common\helpers\ImageHelper;
use yii\db\Expression;
use common\models\goods\AttributeIndex;

/**
 * Class ProvincesController
 * @package api\modules\v1\controllers\member
 */
class StyleController extends OnAuthController
{

    /**
     * @var Provinces
     */
    public $modelClass = Style::class;
    protected $authOptional = ['search','detail','guess-list'];


    /**
     * 款式商品搜索
     * @return array
     */
    public function actionSearch(){
        $sort_map = [
            "price"=>'m.sale_price',//价格
            "carat"=>'m.carat',//石重
            "clarity"=>'m.clarity',//净度
            "cut"=>'m.cut',//切割
            "color"=>'m.color',//颜色
            "sale_volume"=>'m.sale_volume',//销量
        ];


        $type_id = \Yii::$app->request->get("type_id");//产品线ID
//        $page = \Yii::$app->request->get("page",1);//页码
//        $page_size = \Yii::$app->request->get("page_size",14);//每页大小
        $order_param = \Yii::$app->request->get("order_param");//排序参数
        $order_type = \Yii::$app->request->get("order_type", 1);//排序方式 1-升序；2-降序;

        //排序
        $order = '';
        if(!empty($order_param)){
            $order_type = $order_type == 1? "asc": "desc";
            $order = $sort_map[$order_param]. " ".$order_type;
        }

        $fields = ['m.id','lang.style_name','m.goods_images','m.sale_price'];
        $query = Style::find()->alias('m')->select($fields)
            ->leftJoin(StyleLang::tableName().' lang',"m.id=lang.master_id and lang.language='".$this->language."'")
            ->orderby($order);

        $params = \Yii::$app->request->get("params");  //属性帅选
        $params = json_decode($params);
        if(!empty($params)){

            $subQuery = AttributeIndex::find()->alias('a')->select(['a.style_id'])->distinct("a.style_id");
            if($type_id) {
                $subQuery->where(['a.type_id'=>$type_id]);
            }

            $k = 0;
            foreach ($params as $param){
                $value_type = $param->valueType;
                $param_name = $param->paramName;
                $attr_id = $param->paramId;

                //价格不是属性,直接查询主表
                if($param_name == 'sale_price'){
                    $min_price = $param->beginValue;
                    $max_price = $param->endValue;
                    if(is_numeric($min_price)){
                        $query->andWhere(['>','m.sale_price',$min_price]);
                    }
                    if(is_numeric($max_price) && $max_price>0){
                        $query->andWhere(['<=','m.sale_price',$max_price]);
                    }
                    continue;
                }
                if(is_numeric($attr_id)){
                    $k++;
                    $alias = "a".$k; //别名
                    $on = "{$alias}.style_id = a.style_id and {$alias}.attr_id = $attr_id ";
                }else{
                    continue;
                }


                if($value_type == 1){
                    $config_values = $param->configValues;
                    $config_values_str = join(',',$config_values);
                    $subQuery->innerJoin(AttributeIndex::tableName().' '.$alias, $on." and {$alias}.attr_value_id in ({$config_values_str})");
                }else if($value_type == 2){
                    $begin_value = $param->beginValue;
                    $end_value = $param->endValue;
                    $subQuery->innerJoin(AttributeIndex::tableName().' '.$alias, $on." and {$alias}.attr_value > {$begin_value} and {$alias}.attr_value <= {$end_value}");
                }
            }
//            echo $subQuery->createCommand()->getSql();exit;
//            return $subQuery->asArray()->all();
            $query->andWhere(['in','m.id',$subQuery]);

        }
//        echo $query->createCommand()->getSql();exit;
        $result = $this->pagination($query,$this->page, $this->pageSize);
        foreach($result['data'] as & $val) {
            $val['type_id'] = $type_id;
            $val['currency'] = $this->currency;
        }
        return $result;

    }


    //获取推荐图
    public function actionRecommend(){
        $type_id = \Yii::$app->request->get("type_id");//产品线ID
        $limit = \Yii::$app->request->get("limit",4);//查询数量
        $fields = ['m.id', 'm.goods_images', 'lang.style_name','m.sale_price'];
        Style::find()->alias('m')->select($fields)
            ->leftJoin(StyleLang::tableName().' lang',"m.id=lang.master_id and lang.language='".$this->language."'")
            ->where(['m.type_id'=>$type_id])
            ->limit($limit)
            ->all();


    }



    /**
     * 款式商品详情
     * @return mixed|NULL|number[]|string[]|NULL[]|array[]|NULL[][]|unknown[][][]|string[][][]|mixed[][][]|\common\helpers\unknown[][][]
     */
    public function actionDetail()
    {
        $id = \Yii::$app->request->get("id");
        if(empty($id)) {
            return ResultHelper::api(422,"id不能为空");
        }
        $model = Style::find()->where(['id'=>$id])->one();
        if(empty($model)) {
            return ResultHelper::api(422,"商品信息不存在");
        }
        $attr_data = \Yii::$app->services->goods->formatStyleAttrs($model);
        $attr_list = [];
        foreach ($attr_data['style_attr'] as $attr){
            $attr_value = $attr['value'];
            if(empty($attr_value)) {
                continue;
            }
            if(is_array($attr_value)){
                $attr_value = implode('/',$attr_value);
            }
            $attr_list[] = [
                  'name'=>$attr['attr_name'],
                  'value'=>$attr_value,
            ];
        }
        if($model->goods_images) {
            $goods_images = explode(",",$model->goods_images);
            $goods_images = [
                    'big'=>ImageHelper::thumbs($goods_images),
                    'thumb'=>ImageHelper::thumbs($goods_images),
            ];
        }else{
            $goods_images = [];
        }
        $info = [
                'id' =>$model->id,
                'type_id'=>$model->type_id,
                'style_name'=>$model->lang->style_name,
                'style_moq'=>1,
                'sale_price'=>$model->sale_price,
                'currency'=>'$',
                'goods_images'=>$goods_images,
                'goods_3ds'=>$model->style_3ds,
                'style_attrs' =>$attr_list,                
        ];
        $model->goods_clicks = new Expression("goods_clicks+1");
        $model->virtual_clicks = new Expression("virtual_clicks+1");
        $model->save(false);//更新浏览量
        return $info;
    }
    /**
     * 猜你喜欢推荐列表
     */
    public function actionGuessList()
    {
        $style_id= \Yii::$app->request->get("style_id");
        if(empty($style_id)) {
            return ResultHelper::api(422,"style_id不能为空");
        }
        $model = Style::find()->where(['id'=>$style_id])->one();
        if(empty($model)) {
            return [];
        }
        $type_id = $model->type_id;
        $fields = ['s.id','s.style_sn','lang.style_name','s.style_image','s.sale_price','s.goods_clicks'];
        $query = Style::find()->alias('s')->select($fields)
                    ->leftJoin(StyleLang::tableName().' lang',"s.id=lang.master_id and lang.language='".\Yii::$app->language."'")
                    ->andWhere(['s.type_id'=>$type_id])
                    ->andWhere(['<>','s.id',$style_id])
                    ->orderby("s.goods_clicks desc");
        $models = $query->limit(10)->asArray()->all();
        foreach ($models as &$model){
            $model['style_image'] = ImageHelper::thumb($model['style_image']);
            $model['currency'] = '$';
        }
        return $models;        
    }
    
    
    
}