<?php

namespace console\controllers;

use common\models\order\OrderAddress;
use yii\console\Controller;
use common\enums\OrderStatusEnum;
use common\models\order\Order;
use yii\helpers\Console;

/**
 * 订单任务处理
 * Class CommendController
 * @package console\controllers
 */
class OrderController extends Controller
{
    /**
     * 订单超时自动取消
     * @param number $minute
     */
    public function actionOrderTimeoutCancel()
    {
        echo 'Start order timeout cancel ------'.PHP_EOL;
        $total = 0;
        for($page = 1 ; $page <= 100; $page ++) {
            $order_list = Order::find()->select(['id','order_sn'])
                ->where(['order_status'=>OrderStatusEnum::ORDER_UNPAID])
                ->andWhere(['<','created_at',time() - 24*3600])
                ->limit(100)->all();
            
            if(empty($order_list)) {
                break;
            }
            foreach ($order_list as $order) {
                $total++;
                \Yii::$app->services->order->changeOrderStatusCancel($order->id, '超期未支付系统自动关闭订单', 'system','system');
                echo 'Order No:'.$order->order_sn.PHP_EOL;
            }
        }
        echo 'Order cancel total:'.$total.PHP_EOL;
        echo 'End------'.PHP_EOL;
    }

    public function actionSyncPayPalPhone()
    {
        $date = date('Y-m-d H:i:s');
        Console::output("Sync Start--[{$date}]-------------------");
        foreach ($this->getOrderAddress() as $key => $item) {
            $key = 'sync-pay-pal-phone-test1:'.$item->order_id;
            \Yii::$app->cache->getOrSet($key, function () use($item) {
                try{
                    \Yii::$app->services->order->syncPayPalPhone($item);

                    Console::output('success:'.$item->order_id);
                    $result = true;
                } catch (\Exception $exception) {

                    Console::output('fail:'.$item->order_id);
                    Console::output($exception->getMessage());
                    $result = false;
                }
                return $result;
            });
        }
        Console::output('-------------------Sync End-------------------');
    }

    public function getOrderAddress()
    {
        $where1 = ['>', 'id', 0];//id偏移量
        $where2 = ['and'];
//        $where2[] = [
//            'order.is_tourist' => 1,
//            'mobile' => ['',null],
//        ];
//        $where2[] = ['>', 'order_address.created_at', time()-60*45];//45分钟内的订单

        while ($list = OrderAddress::find()->where(array_merge($where2, [$where1]))->joinWith('order')->limit(10)->orderBy('order_id ASC')->all()) {
            foreach ($list as $item) {
                yield $item;
            }
            $where1 = ['>', 'id', $item->order_id];
        }
        return null;
    }
}