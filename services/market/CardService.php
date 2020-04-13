<?php

namespace services\market;

use common\components\Service;
use common\models\market\MarketCard;
use common\models\market\MarketCardDetails;
use common\models\order\Order;
use yii\db\Exception;
use yii\db\Expression;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class CardService
 * @package services\market
 */
class CardService extends Service
{
    //调整金额

    //购物卡消费
    static public function consume($orderId, $cards)
    {
        if(empty($cards)) {
            return;
        }

        if(!($order = Order::findOne($orderId))) {
            return;
        }

        //已使用购物券的金额
        $cardsUseAmount = self::getUseAmount($order->id);

        //订单待支付金额=订单总额-优惠金额
        $amount = $order->account->order_amount - $order->account->discount_amount - $cardsUseAmount;

        //计算订单产品线金额
        $goodsTypeAmounts = [];

        //优惠券使用金额
        $couponUseAmount = 0;

        //订单折扣后金额
        $goodsPayPrice = 0;
        foreach ($order->goods as $goods) {
            $goodsTypeAmounts[$goods['goods_type']] = $goods['goods_pay_price'];
            $goodsPayPrice += $goods['goods_pay_price'];
        }

        foreach ($cards as $card) {

            $cardInfo = MarketCard::findOne(['sn'=>$card['sn']]);
            $balance = $cardInfo->balance;

            if($balance==0) {
                continue;
            }

            $cardUseAmount = 0;

            foreach ($goodsTypeAmounts as $goodsType => &$goodsTypeAmount) {
                if(!empty($cardInfo->goods_type_attach) && in_array($goodsType, $cardInfo->goods_type_attach) && $goodsTypeAmount > 0) {
                    if($goodsTypeAmount >= $balance) {
                        //购物卡余额不足时
                        $cardUseAmount = $balance;
                        $goodsTypeAmount -= $balance;
                        $balance = 0;
                    }
                    else {
                        $cardUseAmount = $goodsTypeAmount;
                        $goodsTypeAmount = 0;
                        $balance -=$goodsTypeAmount;
                    }
                }
            }

            if($cardUseAmount==0) {
                continue;
            }

//            if($cardUseAmount > $cardInfo->balance) {
//
//            }

            //扣除购物卡余额
            $data = [];
            $data['balance'] = new Expression("balance-{$cardUseAmount}");

            $where = ['and'];
            $where[] = ['id'=>$cardInfo->id, 'status'=>1];
            $where[] = ['>=', 'balance', $cardUseAmount];
            if(!MarketCard::updateAll($data, $where)) {
                throw new UnprocessableEntityHttpException("test1");
            }

            //冻结购物卡消费
            $cardDetail = new MarketCardDetails();
            $cardDetail->setAttributes([
                'card_id' => $cardInfo->id,
                'order_id' => $order->id,
                'use_amount' => $cardUseAmount,
                'ip' => $order->ip,
                'member_id' => $order->member_id,
                'type' => 2,
                'status' => 2,
            ]);
            if(!$cardDetail->save()) {
                var_dump($cardDetail->getErrors());exit;
                throw new UnprocessableEntityHttpException($cardDetail->getErrors());
            }

        }
    }

    //获取订单所购物卡金额
    static public function getUseAmount($order_id)
    {
        return MarketCardDetails::find()->where(['order_id'=>$order_id,'status'=>[1,2]])->sum('use_amount');
    }

    //生成卡密码
    static public function generatePw($prefix = '')
    {
        return '123456';//$prefix.str_pad(mt_rand(1, 99999999999),11,'0',STR_PAD_LEFT);
    }

    //生成卡号
    static public function generateSn($prefix = 'BDD')
    {
        return $prefix.date('Y').str_pad(mt_rand(1, 999999),6,'0',STR_PAD_LEFT);
    }

    /**
     * 批量生成购物卡
     * @param array $card 基本数据
     * @param int $count
     * @throws \Exception
     */
    public function generateCards($card, $count=1)
    {
        $card['user_id'] = \Yii::$app->getUser()->id;

        $card['ip'] = \Yii::$app->request->userIP;
        list($card['ip_area_id'],$card['ip_location']) = \Yii::$app->ipLocation->getLocation($card['ip']);

        for ($i = 0; $i < $count; $i++) {
            if(!$this->generateCard($card)) {
                $i--;
            }
        }

        if(MarketCard::find()->where(['batch'=>$card['batch']])->count('id') > $count) {
            throw new UnprocessableEntityHttpException(sprintf("[%s]批次重复生成" , $card['batch']));
        }
    }

    /**
     * 生成购物卡
     * @param array $card 基本数据
     * @return bool
     * @throws \Exception
     */
    private function generateCard($card)
    {
        try {
            $pw = self::generatePw();
            $card['sn'] = self::generateSn();
            $card['balance'] = $card['amount'];

            $newCard = new MarketCard();
            $newCard->setAttributes($card);
            $newCard->setPassword($pw);

            if(!$newCard->save()) {
                throw new UnprocessableEntityHttpException($this->getError($newCard));
            }

//            $result = $newCard->save();

            $cardDetail = [];
            $cardDetail['card_id'] = $newCard->id;
            $cardDetail['balance'] = $card['balance'];
            $cardDetail['use_amount'] = 0;
            $cardDetail['use_amount_cny'] = 0;
            $cardDetail['ip'] = $card['ip'];
            $cardDetail['ip_area_id'] = $card['ip_area_id'];
            $cardDetail['ip_location'] = $card['ip_location'];
            $cardDetail['user_id'] = $card['user_id'];
            $cardDetail['type'] = 1;
            $cardDetail['status'] = 1;

            $newCardDetail = new MarketCardDetails();
            $newCardDetail->setAttributes($cardDetail);
//            $newCardDetail->save();
            if(!$newCardDetail->save()) {
                throw new UnprocessableEntityHttpException($this->getError($newCardDetail));
            }

            $result = true;

        } catch (\Exception $exception) {
            if($exception instanceof UnprocessableEntityHttpException) {
                throw $exception;
            }

            $result = false;
        }
        return $result;
    }

    //导出|导入数据
}