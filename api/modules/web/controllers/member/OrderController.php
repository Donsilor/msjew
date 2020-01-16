<?php

namespace api\modules\web\controllers\member;

use common\helpers\ResultHelper;
use api\controllers\UserAuthController;
use common\models\member\Member;
use yii\base\Exception;
use common\models\order\Order;
use api\modules\web\forms\OrderCreateForm;
use common\enums\OrderStatusEnum;
use common\models\order\OrderAccount;
use common\models\order\OrderAddress;
use common\models\order\OrderGoods;
use services\order\OrderService;

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
        $orderStatus = \Yii::$app->request->get('orderStatus',-1);
        
        $query = Order::find()->where(['member_id'=>$this->member_id]);        
        if($orderStatus && in_array($orderStatus,OrderStatusEnum::getKeys())) {
            if($orderStatus == OrderStatusEnum::ORDER_CONFIRM){
                $orderStatus = [OrderStatusEnum::ORDER_CONFIRM,OrderStatusEnum::ORDER_PAID];
            }            
            $query->andWhere(['order_status'=>$orderStatus]);            
        }
        $query->orderBy('id DESC');
        
        $result = $this->pagination($query, $this->page, $this->pageSize,false);

        $orderList = array();
        foreach ($result['data'] as $order) {
            $exchange_rate = $order->account->exchange_rate;
            $currency = $order->account->currency;
            $orderInfo = [
                'id' =>$order->id,
                'orderNO' =>$order->order_sn,
                'orderStatus'=> $order->order_status,
                'orderAmount'=> $this->exchangeAmount($order->account->order_amount,2,$currency,null, $exchange_rate),
                'productAmount'=> $this->exchangeAmount($order->account->goods_amount,2,$currency,null, $exchange_rate),
                'coinCode'=> $currency,
                'payChannel'=>$order->payment_type,
                'orderTime' =>$order->created_at,
                'details'=>[],
                'paymentType'=>$order->payment_type,
            ];
           $orderGoodsList = OrderGoods::find()->where(['order_id'=>$order->id])->all();
           foreach ($orderGoodsList as $key =>$orderGoods) {
               $orderDetail = [
                       'id' => $orderGoods->id,
                       'orderId'=>$order->id,
                       'groupId'=>null,
                       'groupType'=>null,
                       'goodsId' => $orderGoods->style_id,
                       'goodsDetailId' =>$orderGoods->goods_id,
                       'goodsCode' => $orderGoods->goods_sn,
                       'categoryId'=>$orderGoods->goods_type,
                       'goodsName' => $orderGoods->lang ? $orderGoods->lang->goods_name: $orderGoods->goods_name,
                       'goodsPrice'=>$this->exchangeAmount($orderGoods->goods_price,2,$orderGoods->currency,null,$orderGoods->exchange_rate),
                       'detailType'=>1,
                       'detailSpecs'=>null,
                       'deliveryCount'=>1,
                       'detailCount' => 1,
                       'createTime' => $orderGoods->created_at,
                       'joinCartTime'=>$orderGoods->created_at,
                       'goodsImages'=>$orderGoods->goods_image,
                       'mainGoodsCode'=>null,
                       'ringName'=>"",
                       'ringImg'=>"",
                       'baseConfig'=>null
               ];
               if(!empty($orderGoods->goods_attr)) {
                   $goods_attr = \Yii::$app->services->goods->formatGoodsAttr($orderGoods->goods_attr, $orderGoods->goods_type);
                   $baseConfig = [];
                   foreach ($goods_attr as $vo){
                       $baseConfig[] = [
                               'configId' =>$vo['id'],
                               'configAttrId' =>0,
                               'configVal' =>$vo['attr_name'],
                               'configAttrIVal' =>implode('/',$vo['value']),
                       ];
                   }
                   $orderDetail['baseConfig'] = $baseConfig;
               }
               if(!empty($orderGoods->goods_spec)) {
                   $detailSpecs = [];
                   $goods_spec = \Yii::$app->services->goods->formatGoodsSpec($orderGoods->goods_spec);
                   foreach ($goods_spec as $vo){                       
                       $detailSpecs[] = [
                               'name' =>$vo['attr_name'],
                               'value' =>$vo['attr_value'],                               
                       ];
                   }
                   $orderDetail['detailSpecs'] = json_encode($detailSpecs);
               }
               $orderInfo['details'][] = $orderDetail;
           }
           $orderList[] = $orderInfo;
        }
        $result['data'] = $orderList;        
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
            $ressult = \Yii::$app->services->order->createOrder($model->cart_ids, $this->member_id, $model->buyer_address_id,$model->toArray());
            $trans->commit();
            return [
                    "coinType" => $ressult['currency'],
                    "orderAmount"=> $ressult['order_amount'],
                    "orderId" => $ressult['order_id'],
            ];            
        }catch(Exception $e) {
            
            $trans->rollBack();
            throw $e;
        }
    }
    
    public function actionEdit()
    {
        return $this->edit();
    }
    /**
     * 订单详情
     * @return array
     */
    public function actionDetail()
    {
        $order_id = \Yii::$app->request->get('orderId');
        if(!$order_id) {
            return ResultHelper::api(422, '参数错误:orderId不能为空');
        }      
        $order = Order::find()->where(['id'=>$order_id,'member_id'=>$this->member_id])->one();
        if(!$order){
            return ResultHelper::api(422, '此订单不存在');
        }    
        $currency = $order->account->currency;
        $exchange_rate = $order->account->exchange_rate;
        
        $orderGoodsList = OrderGoods::find()->where(['order_id'=>$order_id])->all();
        $orderDetails = array();
        foreach ($orderGoodsList as $key =>$orderGoods) {

            $orderDetail = [
                'id' => $orderGoods->id,
                'orderId'=>$order->id,
                'groupId'=>null,
                'groupType'=>null,
                'goodsId' => $orderGoods->style_id,
                'goodsDetailId' =>$orderGoods->goods_id,
                'goodsCode' => $orderGoods->goods_sn,
                'categoryId'=>$orderGoods->goods_type,
                'goodsName' => $orderGoods->lang ? $orderGoods->lang->goods_name : $orderGoods->goods_name,
                'goodsPrice'=>$this->exchangeAmount($orderGoods->goods_price,2,$orderGoods->currency,null,$orderGoods->exchange_rate),
                'detailType'=>1,
                'detailSpecs'=>null,
                'deliveryCount'=>1,
                'detailCount' => 1,
                'createTime' => $orderGoods->created_at,
                'joinCartTime'=>$orderGoods->created_at,
                'goodsImages'=>$orderGoods->goods_image,
                'mainGoodsCode'=>null,
                'ringName'=>"",
                'ringImg'=>"",
                'baseConfig'=>null
            ];
            if(!empty($orderGoods->goods_attr)) {
                $goods_attr = \Yii::$app->services->goods->formatGoodsAttr($orderGoods->goods_attr, $orderGoods->goods_type);
                $baseConfig = [];
                foreach ($goods_attr as $vo){
                    $baseConfig[] = [
                        'configId' =>$vo['id'],
                        'configAttrId' =>0,
                        'configVal' =>$vo['attr_name'],
                        'configAttrIVal' =>implode('/',$vo['value']),
                    ];
                }
                $orderDetail['baseConfig'] = $baseConfig;
            }
            if(!empty($orderGoods->goods_spec)) {
                $detailSpecs = [];
                $goods_spec = \Yii::$app->services->goods->formatGoodsSpec($orderGoods->goods_spec);
                foreach ($goods_spec as $vo){
                    $detailSpecs[] = [
                        'name' =>$vo['attr_name'],
                        'value' =>$vo['attr_value'],
                    ];
                }
                $orderDetail['detailSpecs'] = json_encode($detailSpecs);
            }
            $orderDetails[] = $orderDetail;
        }


        $address = array(
                'id' => $order->id,
                'orderId' => $order->id,
                'address' => $order->address->address_details,
                'cityId' => $order->address->city_id,
                'cityName' => $order->address->city_name,
                'countryId' => $order->address->country_id,
                'countryName' => $order->address->country_name,
                'firstName' => $order->address->firstname,
                'lastName' => $order->address->lastname,
                'provinceId' => $order->address->province_id,
                'provinceName' => $order->address->province_name,
                'userAccount' => $order->member->username,
                'userId' => $order->member_id,
                'userMail' => $order->address->email,
                'userTel' => $order->address->mobile,
                'userTelCode' => $order->address->mobile_code,
                'zipCode'=> $order->address->zip_code,
        );

        $order = array(
            'id' => $order->id,
            'address' => $address,
            'addressId' => $address['id'],
            'afterMail' => $order->address->email,
            'coinCode' => $currency,
            'allSend' => 1,            
            'isInvoice'=> 2,            
            'orderNo' => $order->order_sn,
            'orderStatus' => $order->order_status,
            'orderTime' => $order->created_at,
            'orderType' => $order->order_type,            
            'payChannel' => $order->payment_type,
            'productCount' => count($orderDetails),
            'preferFee' => $this->exchangeAmount($order->account->discount_amount,2,$currency,null,$exchange_rate), //优惠金额
            'productAmount' => $this->exchangeAmount($order->account->goods_amount,2,$currency,null,$exchange_rate),            
            'logisticsFee' => $this->exchangeAmount($order->account->shipping_fee,2,$currency,null,$exchange_rate),
            'orderAmount' => $this->exchangeAmount($order->account->order_amount,2,$currency,null,$exchange_rate),
            'otherFee' => $this->exchangeAmount($order->account->other_fee,2,$currency,null,$exchange_rate),
            'safeFee' => $this->exchangeAmount($order->account->safe_fee,2,$currency,null,$exchange_rate),
            'taxFee' => $this->exchangeAmount($order->account->tax_fee,2,$currency,null,$exchange_rate),
            'userId' => $order->member_id,
            'details' => $orderDetails
        );

        return $order;

    }
    /**
     * 订单取消
     * @return mixed|NULL|string
     */
    public function actionCancel(){
        $order_id = \Yii::$app->request->post('orderId');
        if(!$order_id) {
            return ResultHelper::api(422, '请参入正确的订单号');
        }
        $order = Order::find()->where(['id'=>$order_id,'member_id'=>$this->member_id])->one();
        if(!$order){
            return ResultHelper::api(422, '此订单不存在');
        }
        if($order->order_status > OrderStatusEnum::ORDER_UNPAID){
            return ResultHelper::api(422, '此订单不是待付款状态，不能取消');
        }
        $res = Order::updateAll(['order_status'=>OrderStatusEnum::ORDER_CANCEL],['id'=>$order_id,'order_status'=>OrderStatusEnum::ORDER_UNPAID]);
        if($res){
            return 'success';
        }else{
            return ResultHelper::api(422, '取消订单失败');
        }

    }


    /**
     * 确认收货
     * @return mixed|NULL|string
     */
    public function actionConfirmReceipt(){
        $order_id = \Yii::$app->request->post('orderId');
        if(!$order_id) {
            return ResultHelper::api(422, '请参入正确的订单号');
        }
        $order = Order::find()->where(['id'=>$order_id,'member_id'=>$this->member_id])->one();
        if(!$order){
            return ResultHelper::api(422, '此订单不存在');
        }
        if($order->order_status > OrderStatusEnum::ORDER_SEND){
            return ResultHelper::api(422, '此订单不是已发货状态');
        }
        $res = Order::updateAll(['order_status'=>OrderStatusEnum::ORDER_FINISH],['id'=>$order_id,'order_status'=>OrderStatusEnum::ORDER_SEND]);
        if($res){
            return 'success';
        }else{
            return ResultHelper::api(422, '取消订单失败');
        }

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
                'safeFee'=> $this->exchangeAmount($taxInfo['safe_fee']),
                'taxFee'  => $this->exchangeAmount($taxInfo['tax_fee']),
                'planDays' => $taxInfo['plan_days'],
                'currency' => $taxInfo['currency'],
                'exchangeRate'=> $taxInfo['exchange_rate']
        ];
    }
    
}