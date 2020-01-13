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
        
        $query = Order::find()->select(["order.*",'account.*','address.*'])
                    ->leftJoin(OrderAccount::tableName().' account','account.order_id=order.id')
                    ->leftJoin(OrderAddress::tableName().' address','address.order_id=order.id')
                    ->where(['order.member_id'=>$this->member_id]);
        
        if($orderStatus && in_array($orderStatus,OrderStatusEnum::getKeys())) {
            $query->andWhere(['=','order_status',$orderStatus]);
        }

        $query->orderBy('id DESC');
        
        $result = $this->pagination($query, $this->page, $this->pageSize);
        
        $currencySign = $this->getCurrencySign();
        $order_list = array();
        foreach ($result['data'] as $orderRow) {
            $order_id = $orderRow['id'];
            $order = [
                'id' =>$order_id,
                'orderNO' =>$orderRow['order_sn'],
                'orderStatus'=> $orderRow['order_status'],
                'orderAmount'=> $this->exchangeAmount($orderRow['order_amount']),
                'productAmount'=> $this->exchangeAmount($orderRow['goods_amount']),
                'coinCode'=> $currencySign,
                'payChannel'=>$orderRow['payment_type'],
                'orderTime' =>$orderRow['created_at'],
                'details'=>[],  
           ];
           $orderGoodsList = OrderGoods::find()->where(['order_id'=>$order_id])->asArray()->all();
           foreach ($orderGoodsList as $key =>$goodsRow) {
               $id = $goodsRow['id'];
               $goods_id   = $goodsRow['goods_id'];
               $goods_type = $goodsRow['goods_type'];
               $goods = \Yii::$app->services->goods->getGoodsInfo($goods_id, $goods_type,false);
               $orderDetail = [
                       'id' => $id,
                       'orderId'=>$order_id,
                       'groupId'=>null,
                       'groupType'=>null,
                       'goodsId' => $goods['style_id'],
                       'goodsDetailId' =>$goods_id,
                       'goodsCode' => $goodsRow['goods_sn'],
                       'categoryId'=>$goods_type,
                       'goodsName' => $goodsRow['goods_name'],
                       'goodsPrice'=>$this->exchangeAmount($goodsRow['goods_price']),
                       'detailType'=>1,
                       'detailSpecs'=>null,
                       'deliveryCount'=>1,
                       'detailCount' => 1,
                       'createTime' => $orderRow['created_at'],
                       'joinCartTime'=>$orderRow['created_at'],
                       'goodsImages'=>$goodsRow['goods_image'],
                       'mainGoodsCode'=>null,
                       'ringName'=>"",
                       'ringImg'=>"",
                       'baseConfig'=>null
               ];
               if(!empty($goods['goods_attr'])) {
                   $goods_attr = \Yii::$app->services->goods->formatGoodsAttr($goodsRow['goods_attr'], $goods_type);
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
               if(!empty($goods['goods_spec'])) {
                   $detailSpecs = [];
                   $goods_spec = \Yii::$app->services->goods->formatGoodsSpec($goodsRow['goods_spec']);
                   foreach ($goods_spec as $vo){                       
                       $detailSpecs[] = [
                               'name' =>$vo['attr_name'],
                               'value' =>$vo['attr_value'],                               
                       ];
                   }
                   $orderDetail['detailSpecs'] = json_encode($detailSpecs);
               }
               $order['details'][] = $orderDetail;
           }
           $order_list[] = $order;
        }
        $result['data'] = $order_list;        
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
    
    public function actionDetail()
    {
        $order_id = \Yii::$app->request->get('orderId');
        if($order_id == null) {
            return ResultHelper::api(422, '请参入正确的订单号');
        }
        $orderRow = Order::find()->select(["order.*",'account.*','address.*','member.*','address.firstname as a_firstname',
        'address.lastname as a_lastname','address.country_id as a_country_id','address.province_id as a_province_id','address.city_id as a_city_id'])
            ->leftJoin(OrderAccount::tableName().' account','account.order_id=order.id')
            ->leftJoin(OrderAddress::tableName().' address','address.order_id=order.id')
            ->leftJoin(Member::tableName().' member','member.id=order.member_id')
            ->where(['order.member_id'=>$this->member_id, 'order.id'=>$order_id])->asArray()->one();
        if(!$orderRow){
            return ResultHelper::api(422, '此订单不存在');
        }

        $orderGoodsList = OrderGoods::find()->where(['order_id'=>$order_id])->asArray()->all();
        $order_goods_list = array();
        foreach ($orderGoodsList as $key =>$goodsRow) {
            $id = $goodsRow['id'];
            $goods_id   = $goodsRow['goods_id'];
            $goods_type = $goodsRow['goods_type'];
            $goods = \Yii::$app->services->goods->getGoodsInfo($goods_id, $goods_type,false);
            $orderDetail = [
                'id' => $id,
                'orderId'=>$order_id,
                'groupId'=>null,
                'groupType'=>null,
                'goodsId' => $goods['style_id'],
                'goodsDetailId' =>$goods_id,
                'goodsCode' => $goodsRow['goods_sn'],
                'categoryId'=>$goods_type,
                'goodsName' => $goodsRow['goods_name'],
                'goodsPrice'=>$this->exchangeAmount($goodsRow['goods_price']),
                'detailType'=>1,
                'detailSpecs'=>null,
                'deliveryCount'=>1,
                'detailCount' => 1,
                'createTime' => $orderRow['created_at'],
                'joinCartTime'=>$orderRow['created_at'],
                'goodsImages'=>$goodsRow['goods_image'],
                'mainGoodsCode'=>null,
                'ringName'=>"",
                'ringImg'=>"",
                'baseConfig'=>null
            ];
            if(!empty($goods['goods_attr'])) {
                $goods_attr = \Yii::$app->services->goods->formatGoodsAttr($goodsRow['goods_attr'], $goods_type);
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
            if(!empty($goods['goods_spec'])) {
                $detailSpecs = [];
                $goods_spec = \Yii::$app->services->goods->formatGoodsSpec($goodsRow['goods_spec']);
                foreach ($goods_spec as $vo){
                    $detailSpecs[] = [
                        'name' =>$vo['attr_name'],
                        'value' =>$vo['attr_value'],
                    ];
                }
                $orderDetail['detailSpecs'] = json_encode($detailSpecs);
            }
            $order_goods_list[] = $orderDetail;
        }


        $address = array(
            'address' => $orderRow['address_details'],
            'cityId' => $orderRow['a_city_id'],
            'cityName' => $orderRow['city_name'],
            'countryId' => $orderRow['a_country_id'],
            'countryName' => $orderRow['country_name'],
            'firstName' => $orderRow['a_firstname'],
            'lastName' => $orderRow['a_lastname'],
            'id' => $orderRow['order_id'],
            'orderId' => $orderRow['order_id'],
            'provinceId' => $orderRow['a_province_id'],
            'provinceName' => $orderRow['province_name'],
            'userAccount' => $orderRow['username'],
            'userId' => $orderRow['member_id'],
            'userMail' => $orderRow['email'],
            'userTel' => $orderRow['mobile'],
            'userTelCode' => $orderRow['mobile_code'],
            'zipCode'=> '',
        );

        $order = array(
            'address' => $address,
            'addressId' => $orderRow['order_id'],
            'afterMail' => $orderRow['email'],
            'coinCode' => $orderRow['currency'],
            'allSend' => 1,
            'id' => $orderRow['order_id'],
            'isInvoice'=> 2,
            'logisticsFee' => $this->exchangeAmount($orderRow['shipping_fee']),
            'orderAmount' => $this->exchangeAmount($orderRow['order_amount']),
            'orderNo' => $orderRow['order_sn'],
            'orderStatus' => $orderRow['order_status'],
            'orderTime' => $orderRow['created_at'],
            'orderType' => $orderRow['order_type'],
            'otherFee' => $this->exchangeAmount($orderRow['other_fee']),
            'payChannel' => $orderRow['payment_type'],
            'preferFee' => $this->exchangeAmount($orderRow['discount_amount']), //优惠金额
            'productAmount' => $this->exchangeAmount($orderRow['goods_amount']),
            'productCount' => count($order_goods_list),
            'safeFee' => $this->exchangeAmount($orderRow['safe_fee']),
            'taxFee' => $this->exchangeAmount($orderRow['tax_fee']),
            'userId' => $orderRow['member_id'],
            'details' => $order_goods_list
        );

        return $order;

    }

    public function actionCancel(){
        $order_id = \Yii::$app->request->post('orderId');
        if($order_id == null) {
            return ResultHelper::api(422, '请参入正确的订单号');
        }
        $order = Order::find()->where(['member_id'=>$this->member_id,'id'=>$order_id])->one();
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


    //确认收货
    public function actionConfirmReceipt(){
        $order_id = \Yii::$app->request->post('orderId');
        if($order_id == null) {
            return ResultHelper::api(422, '请参入正确的订单号');
        }
        $order = Order::find()->where(['member_id'=>$this->member_id,'id'=>$order_id])->one();
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