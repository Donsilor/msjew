<?php

namespace api\modules\web\controllers\goods;

use common\enums\StatusEnum;
use Yii;
use api\controllers\OnAuthController;
use common\models\goods\Style;
use common\helpers\ResultHelper;
use common\models\goods\StyleLang;
use common\helpers\ImageHelper;
use yii\db\Exception;
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
        $type_id = \Yii::$app->request->post("categoryId");//产品线ID
        if(!$type_id){
            return ResultHelper::api(422, '产品线不能为空');
        }
        $order_param = \Yii::$app->request->post("orderParam");//排序参数
        $order_type = \Yii::$app->request->post("orderType", 1);//排序方式 1-升序；2-降序;

        //排序
        $order = '';
        if(!empty($order_param)){
            $order_type = $order_type == 1? "asc": "desc";
            $order = $sort_map[$order_param]. " ".$order_type;
        }

        $fields = ['m.id','lang.style_name','m.goods_images','m.sale_price'];
        $query = Style::find()->alias('m')->select($fields)
            ->leftJoin(StyleLang::tableName().' lang',"m.id=lang.master_id and lang.language='".$this->language."'")
            ->where(['m.status'=>StatusEnum::ENABLED])->orderby($order);

        $params = \Yii::$app->request->post("params");  //属性帅选

//        $params = json_decode($params);
        if(!empty($params)){

            $subQuery = AttributeIndex::find()->alias('a')->select(['a.style_id'])->distinct("a.style_id");
            if($type_id) {
                $subQuery->where(['a.type_id'=>$type_id]);
            }

            $k = 0;
            foreach ($params as $param){
                $value_type = $param['valueType'];

                $param_name = $param['paramName'];
                //价格不是属性,直接查询主表
                if($param_name == 'sale_price'){
                    $min_price = $param['beginValue'];
                    $max_price = $param['endValue'];
                    if(is_numeric($min_price)){
                        $query->andWhere(['>','m.sale_price',$min_price]);
                    }
                    if(is_numeric($max_price) && $max_price>0){
                        $query->andWhere(['<=','m.sale_price',$max_price]);
                    }
                    continue;
                }
                if(isset($param['paramId']) && is_numeric($param['paramId'])){
                    $attr_id = $param['paramId'];
                    $k++;
                    $alias = "a".$k; //别名
                    $on = "{$alias}.style_id = a.style_id and {$alias}.attr_id = $attr_id ";
                }else{
                    continue;
                }


                if($value_type == 1){
                    $config_values = $param['configValues'];
                    $config_values_str = join(',',$config_values);
                    $subQuery->innerJoin(AttributeIndex::tableName().' '.$alias, $on." and {$alias}.attr_value_id in ({$config_values_str})");
                }else if($value_type == 2){
                    $begin_value = $param['beginValue'];
                    $end_value = $param['endValue'];
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
            $arr = array();
            $arr['id'] = $val['id'];
            $arr['categoryId'] = $type_id;
            $arr['coinType'] = $this->currency;
            $arr['goodsImages'] = $val['goods_images'];
            $arr['salePrice'] = $val['sale_price'];
            $arr['goodsName'] = $val['style_name'];
            $arr['isJoin'] = null;
            $arr['specsModels'] = null;
            $val = $arr;
        }
        return $result;

    }


    //商品推荐
    public function actionRecommend(){
        $type_id = \Yii::$app->request->get("type_id");//产品线ID
        if(!$type_id){
            return ResultHelper::api(422, '产品线不能为空');
        }
        $recommend_type = \Yii::$app->request->get("recommend_type",2);//产品线ID
        $limit = \Yii::$app->request->get("limit",4);//查询数量
        $fields = ['m.id', 'm.goods_images', 'lang.style_name','m.sale_price'];
        $result = Style::find()->alias('m')->select($fields)
            ->leftJoin(StyleLang::tableName().' lang',"m.id=lang.master_id and lang.language='".$this->language."'")
            ->where(['and',['m.type_id'=>$type_id],['like','m.recommend_type',$recommend_type],['m.status'=>StatusEnum::ENABLED]])
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
        try{
            $style = \Yii::$app->services->goods->formatStyleGoodsById($id, $this->language);
            $recommend_style = Style::find()->alias('m')
                ->leftJoin(StyleLang::tableName().' lang',"m.id=lang.master_id and lang.language='".$this->language."'")
                ->where(['and',['m.status'=>StatusEnum::ENABLED],['<>','m.id',$id],['=','m.type_id',$model->type_id]])
                ->orderBy('m.goods_clicks desc')
                ->select(['m.id','m.goods_images','m.sale_price','lang.style_name'])
                ->limit(4)->all();

            foreach ($recommend_style as $val){
                $recommend = array();
                $recommend['id'] = $val->id;
                $recommend['goodsName'] = $val->lang->style_name;
                $recommend['categoryId'] = $model->type_id;
                $recommend['salePrice'] = $val->sale_price;
                $recommend['goodsImages'] = $val->goods_images;
                $recommend['isJoin'] = null;
                $recommend['specsModels'] = null;
                $recommend['coinType'] = $this->currency;
                $style['recommends'][] = $recommend;
            }


            $model->goods_clicks = new Expression("goods_clicks+1");
            $model->virtual_clicks = new Expression("virtual_clicks+1");
            $model->save(false);//更新浏览量
            return $style;


        }catch (Exception $e){
            $error = $e->getMessage();
            return ResultHelper::api(422, $error);
        }

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