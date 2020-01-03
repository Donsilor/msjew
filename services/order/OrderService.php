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
    public function createOrder($cart_ids,$buyer_id, $buyer_address_id, $order_info)
    {
        $orderAccountTax = $this->getOrderAccountTax($cart_ids, $buyer_id, $buyer_address_id);
        if(empty($orderAccountTax['buyerAddress'])) {
            throw new UnprocessableEntityHttpException("收货地址不能为空");
        }
        $buyerAddress = $orderAccountTax['buyerAddress'];
        $orderGoodsList   = $orderAccountTax['orderGoodsList'];
        //订单
        $order = new Order();
        $order->attributes = $order_info;
        $order->language   = \Yii::$app->params['language'];
        $order->member_id = $buyer_id;
        $order->order_sn  = 'BDD'.time();
        if(false === $order->save()){
            throw new UnprocessableEntityHttpException($this->getError($order));
        }
        //订单商品       
        foreach ($orderGoodsList as $goods) {
            $orderGoods = new OrderGoods();
            $orderGoods->attributes = $goods;
            $orderGoods->order_id = $order->id;           
            if(false === $orderGoods->save()){
                throw new UnprocessableEntityHttpException($this->getError($orderGoods));
            }
        }
        //金额校验
        if($order_info['order_amount'] != $orderAccountTax['order_amount']) {
            throw new UnprocessableEntityHttpException("订单金额校验失败：订单金额有变动，请刷新页面查看");
        }
                
        $orderAccount = new OrderAccount();
        $orderAccount->attributes = $orderAccountTax;
        $orderAccount->order_id = $order->id;
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
    /**
     * 获取订单金额，税费信息
     * @param unknown $cart_ids
     * @param unknown $buyer_id
     * @param unknown $buyer_address_id
     * @param number $promotion_id
     * @throws UnprocessableEntityHttpException
     * @return array
     */
    public function getOrderAccountTax($cart_ids, $buyer_id, $buyer_address_id, $promotion_id = 0)
    {
        if($cart_ids && !is_array($cart_ids)) {
            $cart_ids = explode(',', $cart_ids);
        }
        
        $cart_list = OrderCart::find()->where(['member_id'=>$buyer_id,'id'=>$cart_ids])->all();
        if(empty($cart_list)) {
            throw new UnprocessableEntityHttpException("订单商品查询失败");
        }
        $buyerAddress = Address::find()->where(['id'=>$buyer_address_id,'member_id'=>$buyer_id])->one();
        $orderGoodsList = [];
        foreach ($cart_list as $cart) {
            
            $goods = \Yii::$app->services->goods->getGoodsInfo($cart->goods_id,$cart->goods_type);
            if(empty($goods) || $goods['status'] != 1) {
                throw new UnprocessableEntityHttpException("当前选购商品已下架");
            }            
            $goods_amount += $goods['sale_price'];
            $orderGoodsList[] = [
                    'goods_id' => $cart->goods_id,
                    'goods_sn' => $goods['goods_sn'],
                    'style_sn' => $goods['style_sn'],
                    'goods_name' => $goods['goods_name'],
                    'goods_price' => $goods['sale_price'],
                    'goods_pay_price' => $goods['sale_price'],
                    'goods_num' => $cart->goods_num,
                    'goods_type' => $cart->goods_type,
                    'goods_image' => $goods['goods_image'],
                    'promotions_id' => 0,
            ];
        }
        //金额
        $discount_amount = 0;//优惠金额
        $shipping_fee = 0;//运费
        $tax_fee = 0;//税费
        $safe_fee = 0;//保险费
        $other_fee = 0;//其他费用
        
        $order_amount = $goods_amount + $shipping_fee + $tax_fee + $safe_fee + $other_fee;//订单总金额 

        return [
                'shipping_fee' => $shipping_fee,
                'order_amount'  => $order_amount,           
                'goods_amount' => $goods_amount,
                'safe_fee' =>$safe_fee,
                'tax_fee'  =>$tax_fee,
                'discount_amount'=>$discount_amount,
                'plan_days' =>'1-12',
                'buyerAddress'=>$buyerAddress,
                'orderGoodsList'=>$orderGoodsList,
        ];
    }
          
    
}