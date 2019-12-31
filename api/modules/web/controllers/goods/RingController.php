<?php

namespace api\modules\web\controllers\goods;

use api\modules\web\forms\AttrSpecForm;
use common\enums\StatusEnum;
use common\models\goods\Diamond;
use common\models\goods\DiamondLang;
use common\models\goods\Goods;
use common\models\goods\Ring;
use common\models\goods\RingLang;
use common\models\goods\RingRelation;
use common\models\goods\Style;
use Yii;
use api\controllers\OnAuthController;
use common\helpers\ResultHelper;
use common\models\goods\StyleLang;
use common\helpers\ImageHelper;
use yii\base\Exception;
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
        //$type_id = \Yii::$app->request->get("type_id", 12);//产品线ID
        $order_param = \Yii::$app->request->post("orderParam");//排序参数
        $order_type = \Yii::$app->request->post("orderType", 1);//排序方式 1-升序；2-降序;

        //排序
        $order = '';
        if(!empty($order_param)){
          $order_type = $order_type == 1? "asc": "desc";
          $order = $sort_map[$order_param]. " ".$order_type;
        }


        $fields = ['m.id','m.ring_sn','lang.ring_name','m.ring_images','m.sale_price','m.ring_style','m.status'];
        $query = Ring::find()->alias('m')->select($fields)
            ->innerJoin(RingLang::tableName().' lang',"m.id=lang.master_id and lang.language='".$this->language."'");



        //筛选条件
        $ring_style = \Yii::$app->request->post("styleValue", 1);//对戒款式
        $begin_price = \Yii::$app->request->post("beginPrice",0);//开始价格
        $end_price = \Yii::$app->request->post("endPrice");//结束价格
        $material = \Yii::$app->request->post("materialValue");//成色Id
        $query->andWhere(['=','m.ring_style', $ring_style]);
        if($begin_price && $end_price){
            $query->andWhere(['between','m.sale_price', $begin_price, $end_price]);
        }

        if(is_numeric($material) && $material > 0){
            $query->innerJoin(RingRelation::tableName()." r", 'r.ring_id = m.id')
                ->innerJoin(AttributeIndex::tableName().' at','at.style_id=r.style_id')
                ->andWhere(['at.attr_value_id'=>$material,'m.status'=>StatusEnum::ENABLED]);
        }

        //排序
        if($order != '')  $query->orderby($order);
        $query->distinct('m.id');

        $result = $this->pagination($query,$this->page,$this->pageSize);

        foreach($result['data'] as & $val) {
            $arr = array();
            $arr['coinType'] = $this->currencySign;
            $arr['id'] = $val['id'];
            $arr['ringCode'] = $val['ring_sn'];
            $arr['ringImg'] = $val['ring_images'];
            $arr['ringStyle'] = $val['ring_style'];
            $arr['salePrice'] = $val['sale_price'];
            $arr['status'] = $val['status'];
            $val = $arr;
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
            $val['coinType'] = $this->currencySign;
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
        $field = ['m.id','m.status','m.ring_sn','lang.ring_name','lang.ring_body','lang.meta_title','lang.meta_desc','lang.meta_word','m.ring_sn',
            'm.ring_images','m.sale_price','m.ring_style'];
        $model = $result = Ring::find()->alias('m')->select($field)
            ->leftJoin(RingLang::tableName().' lang',"m.id=lang.master_id and lang.language='".$this->language."'")
            ->where(['m.id'=>$id,'m.status'=>StatusEnum::ENABLED])
            ->one();
        if(empty($model)) {
            return ResultHelper::api(422,"对戒信息不存在");
        }
        $ring = array();
        $ring['id'] = $model->id;
        $ring['name'] = $model->lang->ring_name;
        $ring['ringImg'] = $model->ring_images;
        $ring['ringCode'] = $model->ring_sn;
        $ring['salePrice'] = $model->sale_price;
        $ring['coinType'] = $this->currencySign;
        $ring['status'] = $model->status;
        $ring['metaDesc'] = $model->lang->meta_desc;
        $ring['metaTitle'] = $model->lang->meta_title;
        $ring['metaWord'] = $model->lang->meta_word;
        $ring['ringStyle'] = $model->ring_style;
        try{
            $goodsModels = array();
            $searchGoodsModels = array();

            $style_ids = RingRelation::find()
                ->where(['ring_id'=>$id])->asArray()->select(['style_id'])->all();
            foreach ($style_ids as $style_id){
                $style = \Yii::$app->services->goods->formatStyleGoodsById($style_id, $this->language);
                $goodsModels[] = $style;
                $searchGoods = array();
                $searchGoods['categoryId'] = $style['categoryId'];
                $searchGoods['coinType'] = $style['coinType'];
                $searchGoods['goodsImages'] = $style['goodsImages'];
                $searchGoods['goodsName'] = $style['goodsName'];
                $searchGoods['id'] = $style['id'];
                $searchGoods['isJoin'] = null;
                $searchGoods['salePrice'] = $style['salePrice'];
                $searchGoods['specsModels'] = null;
                $searchGoodsModels[] = $searchGoods;
            }

            $ring['goodsModels'] = $goodsModels;
            $ring['searchGoodsModels'] = $searchGoodsModels;
            $model->goods_clicks = new Expression("goods_clicks+1");
            $model->virtual_clicks = new Expression("virtual_clicks+1");
            $model->save(false);//更新浏览量
            return $ring;
        }catch (Exception $e){
            $error = $e->getMessage();
            return ResultHelper::api(422, $error);
        }

    }




}