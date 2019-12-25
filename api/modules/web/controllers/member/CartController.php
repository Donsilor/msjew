<?php

namespace api\modules\web\controllers\member;

use api\controllers\OnAuthController;
use common\models\order\Cart;


/**
 * 购物车
 *
 * Class SiteController
 * @package api\modules\v1\controllers
 */
class CartController extends OnAuthController
{
    
    public $modelClass = Cart::class;
    
    protected $authOptional = [];

    /**
     * 购物车列表
     */
    public function actionIndex()
    {
        return \Yii::$app->user;
    }
    /**
     * 添加购物车商品
     */
    public function actionCreate()
    {
        
    }
    /**
     * 删除购物车商品
     */
    public function actionDelete($id)
    {
        
    }
    /**
     * 清空购物车
     */
    public function actionClear()
    {
        
    }
    
}