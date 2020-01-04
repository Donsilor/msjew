<?php

namespace api\modules\web\controllers\member;

use common\helpers\ResultHelper;
use api\controllers\UserAuthController;
use yii\base\Exception;
use common\models\order\Order;
use api\modules\web\forms\OrderCreateForm;
use common\enums\OrderStatusEnum;

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
    
    
    public function actionIndex()
    {
        $orderStatus = \Yii::$app->request->get('orderStatus');
        
        $query = $this->modelClass::find()->select(["*"])->where(['member_id'=>$this->member_id]);
        
        if(in_array($orderStatus,OrderStatusEnum::getKeys())) {
            $query->andWhere(['=','order_status',$orderStatus]);
        }
        $result = $this->pagination($query , $this->page,$this->pageSize);
        
        $order_list = $result['data'];
        
        
        return $result;
    }
    /**
     * 创建订单
     * {@inheritDoc}
     */
    public function actionCreate()
    {
        try{
            $trans = \Yii::$app->db->beginTransaction();
            
            $model = new OrderCreateForm();
            $model->attributes = \Yii::$app->request->post();
            if(!$model->validate()) {
                return ResultHelper::api(422,$this->getError($model));
            }
            $order = \Yii::$app->services->order->createOrder($model->cart_ids, $this->member_id, $model->buyer_address_id,$model->toArray());
            $trans->commit();
            return $order;
        }catch(Exception $e) {
            $trans->rollBack();
            throw $e;
        }
    }
    
    public function actionEdit()
    {
        return $this->edit();
    }
    
    public function actionInfo()
    {
        
    }
    
    /**
     * 订单金额税费信息
     * @return array
     */
    public function actionTax()
    {
        $cartIds = \Yii::$app->request->get("cartIds");
        $addressId = \Yii::$app->request->get("addressId");
        if(empty($cartIds)) {
            return ResultHelper::api(422,"cartIds不能为空");
        }
        $taxInfo = \Yii::$app->services->order->getOrderAccountTax($cartIds, $this->member_id, $addressId);        
        return [
                'logisticsFee' => $this->exchangeAmount($taxInfo['shipping_fee']),
                'orderAmount'  => $this->exchangeAmount($taxInfo['order_amount']),                
                'productAmount' => $this->exchangeAmount($taxInfo['goods_amount']),
                'safeFee'=>$this->exchangeAmount($taxInfo['safe_fee']),
                'taxFee'  =>$this->exchangeAmount($taxInfo['tax_fee']),
                'planDays' =>$taxInfo['plan_days'],
                'currency' =>$taxInfo['currency'],
                'exchangeRate'=>$taxInfo['exchange_rate']
        ];
    }
    
}