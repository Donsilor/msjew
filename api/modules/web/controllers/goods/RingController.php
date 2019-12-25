<?php

namespace api\modules\web\controllers\goods;

use api\modules\web\forms\AttrSpecForm;
use common\enums\StatusEnum;
use common\models\goods\Diamond;
use common\models\goods\DiamondLang;
use common\models\goods\Ring;
use common\models\goods\RingLang;
use Yii;
use api\controllers\OnAuthController;
use common\helpers\ResultHelper;
use common\models\goods\StyleLang;
use common\helpers\ImageHelper;
use yii\db\Expression;
use common\models\goods\AttributeIndex;

/**
 * Class ProvincesController
 * @package api\modules\web\controllers\goods
 */
class RingController extends OnAuthController
{

    /**
     * @var Provinces
     */
    public $modelClass = Ring::class;
    protected $authOptional = ['search','recommend','detail','guess-list'];

    /**
     * 款式商品搜索
     * @return array
     */

    public function actionSearch(){
        $sort_map = [
            "price"=>'m.sale_price',//价格
            "sale_volume"=>'m.sale_volume',//销量
        ];
        $params_map = [
            'shape'=>'m.shape',//形状
            'sale_price'=>'m.sale_price',//销售价
            'carat'=>'m.carat',//石重
            'cut'=>'m.cut',//切工
            'color'=>'m.color',//颜色
            'clarity'=>'m.clarity',//净度
            'polish'=>'m.polish',//光澤--抛光
            'symmetry'=>'m.symmetry',//对称
            'card'=>'m.cert_type',//证书类型
            'depth'=>'m.depth_lv',//深度
            'table'=>'m.table_lv',//石面--台宽
            'fluorescence'=>'m.fluorescence',//荧光
        ];

        $type_id = \Yii::$app->request->get("type_id");//产品线ID
        if(!$type_id){
            return ResultHelper::api(422, '产品线不能为空');
        }
        $order_param = \Yii::$app->request->get("order_param");//排序参数
        $order_type = \Yii::$app->request->get("order_type", 1);//排序方式 1-升序；2-降序;

        //排序
        $order = '';
        if(!empty($order_param)){
          $order_type = $order_type == 1? "asc": "desc";
          $order = $sort_map[$order_param]. " ".$order_type;
        }


        $fields = ['m.id','m.goods_id','m.goods_sn','lang.goods_name','m.goods_image','m.sale_price','m.goods_sn'
                    ,'m.carat','m.cert_id','m.depth_lv','m.table_lv','m.clarity','m.cert_type','m.color'
                    ,'m.cut','m.fluorescence','m.polish','m.shape','m.symmetry'];
        $query = Diamond::find()->alias('m')->select($fields)
            ->leftJoin(DiamondLang::tableName().' lang',"m.id=lang.master_id and lang.language='".$this->language."'")
            ->orderby($order);

        $params = \Yii::$app->request->get("params");  //属性帅选
        $params = json_decode($params);
        if(!empty($params)){
            foreach ($params as $param){
                $value_type = $param->valueType;
                $param_name = $param->paramName;
                if($value_type == 1){
                    $config_values = $param->configValues;
                    $query->andWhere(['in',$params_map[$param_name], $config_values]);
                }else if($value_type == 2){
                    $begin_value = $param->beginValue;
                    $end_value = $param->endValue;
                    $query->andWhere(['between',$params_map[$param_name], $begin_value, $end_value]);
                }
            }
        }
        $result = $this->pagination($query,$this->page,$this->pageSize);
        foreach($result['data'] as & $val) {
            $val['type_id'] = $type_id;
            $val['currency'] = $this->currency;
            $val['clarity'] = \Yii::$app->attr->valueName($val['clarity']);
            $val['cert_type'] = \Yii::$app->attr->valueName($val['cert_type']);
            $val['color'] = \Yii::$app->attr->valueName($val['color']);
            $val['cut'] = \Yii::$app->attr->valueName($val['cut']);
            $val['fluorescence'] = \Yii::$app->attr->valueName($val['fluorescence']);
            $val['polish'] = \Yii::$app->attr->valueName($val['polish']);
            $val['shape'] = \Yii::$app->attr->valueName($val['shape']);
            $val['symmetry'] = \Yii::$app->attr->valueName($val['symmetry']);

        }
        return $result;

    }



    //商品推荐
    public function actionRecommend(){
        $type_id = \Yii::$app->request->get("type_id",12);//产品线ID
        if(!$type_id){
            return ResultHelper::api(422, '产品线不能为空');
        }
        $recommend_type = \Yii::$app->request->get("recommend_type",2);//产品线ID
        $limit = \Yii::$app->request->get("limit",4);//查询数量
        $fields = ['m.id', 'm.ring_images', 'lang.ring_name','m.sale_price'];
        $result = Ring::find()->alias('m')->select($fields)
            ->leftJoin(RingLang::tableName().' lang',"m.id=lang.master_id and lang.language='".$this->language."'")
            ->where(['and',['like','m.recommend_type',$recommend_type],['m.status'=>StatusEnum::ENABLED]])
            ->limit($limit)->asArray()->all();
        foreach($result as & $val) {
            $val['type_id'] = $type_id;
            $val['currency'] = $this->currency;
        }
        return $result;

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