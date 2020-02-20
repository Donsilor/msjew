<?php

namespace services\order;

use common\components\Service;
use common\enums\OrderTouristStatusEnum;
use common\models\order\OrderTourist;
use common\models\order\OrderTouristDetails;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class OrderTouristService
 * @package services\order
 */
class OrderTouristService extends Service
{


    /**
     * @param $cart_list
     */
    public function createOrder($cart_list)
    {

        $goods_amount = 0;
        $details = [];
        foreach ($cart_list as $item) {
            $goods = \Yii::$app->services->goods->getGoodsInfo($item['goods_id'], $item['goods_type']);

            //商品价格
            $sale_price = $this->exchangeAmount($goods['sale_price']);
            $goods_amount += $sale_price;

            $detail = new OrderTouristDetails();
            $detail->goods_id = $item['goods_id'];//商品ID
            $detail->goods_type = $goods['type_id'];//产品线
            $detail->goods_price = $sale_price;//价格
            $detail->goods_num = $item['goods_num'];//数量
            $detail->group_id = $item['group_id'];//组ID
            $detail->group_type = $item['group_type'];//分组类型
            $detail->goods_spec = json_encode($goods['goods_spec']);//商品规格

            $details[] = $detail;
        }

        //金额
        $discount_amount = 0;//优惠金额
        $shipping_fee = 0;//运费
        $tax_fee = 0;//税费
        $safe_fee = 0;//保险费
        $other_fee = 0;//其他费用

        $order_amount = $goods_amount + $shipping_fee + $tax_fee + $safe_fee + $other_fee;//订单总金额

        //保存订单信息
        $order = new OrderTourist();
        $order->merchant_id = null;//商铺ID
        $order->store_id = null;//店铺ID
        $order->tourist_key = null;//游客的KEY

        $order->order_amount = $order_amount;//订单金额
        $order->goods_amount = $goods_amount;//商品总金额
        $order->discount_amount = $discount_amount;//优惠金额
        $order->pay_amount = 0;//实际支付金额
        $order->refund_amount = 0;//退款金额
        $order->shipping_fee = $shipping_fee;//运费
        $order->tax_fee = $tax_fee;//税费
        $order->safe_fee = $safe_fee;//保险费
        $order->other_fee = $other_fee;//附加费

        $order->currency = $this->getCurrency();//货币
        $order->exchange_rate = $this->getExchangeRate();//汇率
        $order->language   = $this->getLanguage();//语言
        $order->ip = \Yii::$app->request->userIP;  //用户下单ip
        $order->status = OrderTouristStatusEnum::ORDER_UNPAID;  //状态

        //保存订单
        if(false === $order->save()) {
            throw new UnprocessableEntityHttpException($this->getError($order));
        }

        foreach ($details as $detail) {
            //订单ID
            $detail->order_tourist_id = $order->id;

            //保存订单详情
            if(false === $detail->save()) {
                throw new UnprocessableEntityHttpException($this->getError($detail));
            }
        }

        return $order->id;
    }
}