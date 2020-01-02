<?php

namespace services\order;

use common\components\Service;
use common\models\order\OrderCart;
use yii\web\UnprocessableEntityHttpException;
use common\models\order\OrderGoods;
use common\models\order\Order;
use common\models\member\Member;
use common\enums\StatusEnum;
use common\models\member\Address;
use common\models\order\OrderAccount;
use common\models\order\OrderAddress;

/**
 * Class OrderService
 * @package services\order
 */
class OrderService extends Service
{
    /**
     * 创建订单
     * @param array $cart_ids
     * @param array $order_info
     * @param int $buyer_id
     * @param int $buyer_address_id
     */
    public function createOrder($cart_ids, $order_info, $buyer_id, $buyer_address_id)
    {
        $buyer = Member::find()->where(['id'=>$buyer_id,'status'=>StatusEnum::ENABLED])->one();
        if(empty($buyer)) {
            throw new UnprocessableEntityHttpException("用户信息不存在");
        }
        $cart_list = OrderCart::find()->where(['member_id'=>$buyer->id,'id'=>$cart_ids])->all();
        if(empty($cart_list)) {
            throw new UnprocessableEntityHttpException("订单商品不存在");
        }
        $buyerAddress = Address::find()->where(['id'=>$buyer_address_id,'member_id'=>$buyer->id])->one();
        if(empty($buyerAddress)) {
            throw new UnprocessableEntityHttpException("收货地址不能为空");
        }        
        //订单
        $order = new Order();
        $order->attributes = $order_info;
        if(false === $order->save()){
            throw new UnprocessableEntityHttpException($this->getError($order));
        }
        //订单商品       
        $goods_amount = 0;//商品总金额
        foreach ($cart_list as $cart) {
            
            $goods = \Yii::$app->services->goods->getGoodsInfo($cart->goods_id,$cart->goods_type);
            if(empty($goods) || $goods['status'] != 1) {
                throw new UnprocessableEntityHttpException("商品已下架");
            }    

            $orderGoods = new OrderGoods();
            $orderGoods->order_id = $order->id;
            $orderGoods->goods_id = $goods['goods_id'];
            $orderGoods->goods_sn = $goods['goods_sn'];
            $orderGoods->style_sn = $goods['style_sn'];
            $orderGoods->goods_name = $goods['goods_name'];
            $orderGoods->goods_price = $goods['sale_price'];
            $orderGoods->goods_pay_price = $goods['sale_price'];
            $orderGoods->goods_num = $cart->goods_num;
            $orderGoods->goods_type = $cart->goods_type;
            $orderGoods->goods_image = $goods['goods_image'];
            $orderGoods->promotions_id = 0;
            if(false === $orderGoods->save()){
                throw new UnprocessableEntityHttpException($this->getError($orderGoods));
            }
            $goods_amount += $goods['sale_price'];
        }
        //金额
        $discount_amount = 0;//优惠金额
        $shipping_fee = 0;//运费
        $tax_fee = 0;//税费
        $safe_fee = 0;//保险费
        $other_fee = 0;//其他费用
        
        $order_amount = $goods_amount + $shipping_fee + $tax_fee + $safe_fee + $other_fee;//订单总金额 
        $_order_amount = $this->exchangeAmount($order_amount);
        //金额校验
        if($order_info['order_amount'] != $_order_amount) {
            throw new UnprocessableEntityHttpException("订单金额校验失败：订单金额有变动，请刷新页面查看");
        }
                
        $orderAccount = new OrderAccount();
        $orderAccount->attributes = $order_info;
        $orderAccount->order_id = $order->id;
        $orderAccount->goods_amount = $goods_amount;
        $orderAccount->discount_amount = $discount_amount;
        $orderAccount->shipping_fee = $shipping_fee;//运费
        $orderAccount->tax_fee = $tax_fee;//税费
        $orderAccount->safe_fee = $safe_fee;//保险费
        $orderAccount->other_fee = $other_fee;//其他费用
        //订单总金额
        $order_amount = $goods_amount + $shipping_fee;
        $orderAccount->order_amount = $order_amount;
        if(false === $orderAccount->save()){
            throw new UnprocessableEntityHttpException($this->getError($orderAccount));
        }
        //订单地址
        $orderAddress = new OrderAddress();
        $orderAddress->attributes = $buyerAddress->toArray();
        $orderAddress->order_id   = $order->id;
        if(false === $orderAddress->save()) {
            throw new UnprocessableEntityHttpException($this->getError($orderAddress));
        }
        return $order;
    }
          
    
}