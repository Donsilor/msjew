<?php

namespace api\modules\web\controllers\member;

use common\helpers\ResultHelper;
use api\controllers\UserAuthController;
use yii\base\Exception;
use common\models\order\Order;
use api\modules\web\forms\OrderCreateForm;

/**
 * 用户订单
 *
 * Class SiteController
 * @package api\modules\v1\controllers
 */
class OrderController extends UserAuthController
{
    
    public $modelClass = Order::class;
    
    protected $authOptional = [];
    
    
    public function index()
    {
        
    }
    /**
     * 创建订单
     * {@inheritDoc}
     * @see \api\controllers\OnAuthController::add()
     */
    public function add()
    {
        try{
            $trans = \Yii::$app->db->beginTransaction();
            
            $model = new OrderCreateForm();
            $model->attributes = \Yii::$app->request->post();
            if(!$model->validate()) {
                return ResultHelper::api(422,$this->getError($model));
            }
            $order = \Yii::$app->services->order->createOrder($model->cart_ids,$model->toArray(), $this->member_id, $model->buyer_address_id);
            $trans->commit();
            return $order;
        }catch(Exception $e) {
            $trans->rollBack();
            throw $e;
        }
    }
    
    public function edit()
    {
        return $this->edit();
    }
    
    public function info()
    {
        
    }
    
}