<?php

namespace api\modules\web\controllers\member;

use common\models\order\Cart;
use api\modules\web\forms\CartForm;
use common\helpers\ResultHelper;
use api\controllers\UserAuthController;


/**
 * 购物车
 *
 * Class SiteController
 * @package api\modules\v1\controllers
 */
class CartController extends UserAuthController
{
    
    public $modelClass = Cart::class;
    
    protected $authOptional = [];

    /**
     * 购物车列表
     */
    public function actionIndex()
    {
        $models = Cart::find()->where(['member_id'=>$this->member_id])->all();
        $cart_list = array();
        foreach ($models as $model) {
            
            $goods = \Yii::$app->services->goods->getGoodsInfo($model->goods_id,$model->goods_type);
            if(empty($goods)) {
                continue;
            }
            $cart = array();
            $cart['id'] = $model->id;
            $cart['goods_id'] = $model->goods_id;
            $cart['goods_sn'] = $goods['goods_sn'];
            $cart['goods_type'] = $model->goods_type;
            $cart['goods_image'] = $goods['goods_image'];
            $cart['goods_name'] = $goods['goods_name'];
            $cart['goods_price'] = $goods['sale_price'];
            $cart['currency']   = $this->currency;
            $cart['goods_num'] = $model->goods_num;
            $cart['group_type'] = $model->group_type;
            $cart['group_id'] = $model->group_id;
            $cart['goods_spec'] = $goods['goods_spec'];            
            $cart_list[] = $cart;
        }
        return $cart_list;
    }
    /**
     * 添加购物车商品
     */
    public function actionAdd()
    {
        $model = new CartForm();
        $model->attributes = \Yii::$app->request->post();
        if (!$model->validate()) {
            // 返回数据验证失败
            return ResultHelper::api(422, $this->getError($model));
        }
        
        $goods = \Yii::$app->services->goods->getGoodsInfo($model->goods_id,$model->goods_type);
        if(!$goods || empty($goods['status'])) {
            return ResultHelper::api(423,"添加失败，商品不是售卖状态");
        }

        $cart = $this->modelClass::find()->where(['goods_id'=>$model->goods_id,'goods_type'=>$goods['type_id']])->one();
        if($cart) {
            $cart->goods_num = $cart->goods_num + $model->goods_num;
        }else {
            $cart = new Cart();
            $cart->attributes = $model->toArray();
            $cart->merchant_id = $this->merchant_id;
            $cart->member_id = $this->member_id;
        }
        $cart->goods_type = $goods['type_id'];
        $cart->goods_price = $goods['sale_price'];
        $cart->goods_spec = json_encode($goods['goods_spec']);//商品规格
        
        if (!$cart->save()) {
            return ResultHelper::api(422, $this->getError($model));
        }        
        return $cart;        
    }
    /**
     * 编辑购物车
     * @return mixed|NULL
     */
    public function actionEdit()
    {
        return $this->edit(['goods_num'])->toArray(['id','goods_num']);
    }   
    
    /**
     * 删除购物车商品
     */
    public function actionDel()
    {
        $id = \Yii::$app->request->post("id");
        if(!$id) {
            return ResultHelper::api(422, "id不能为空");
        }        
        if($id == -1) {
            //清空购物车
            $num = Cart::deleteAll(['member_id'=>$this->member_id]);
        }else {  
            if(!is_array($id)) {
                $id = explode(',', $id);
            }
            $num = Cart::deleteAll(['member_id'=>$this->member_id,'id'=>$id]);
        }
        return ['num'=>$num];
    } 
     
    
}