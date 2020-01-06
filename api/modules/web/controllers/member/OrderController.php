<?php

namespace api\modules\web\controllers\member;

use common\helpers\ResultHelper;
use api\controllers\UserAuthController;
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
        
        $result = $this->pagination($query, $this->page, $this->pageSize);
        
        $currencySign = $this->getCurrencySign();
        $order_list = array();
        foreach ($result['data'] as $orderRow) {
            $order_id = $orderRow['id'];
            $order = [
                'id' =>$order_id,
                'orderNO' =>$orderRow['order_sn'],
                'orderStatus'=> $orderRow['order_status'],
                'orderAmount'=> $orderRow['order_amount'],
                'productAmount'=> $orderRow['goods_amount'],
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