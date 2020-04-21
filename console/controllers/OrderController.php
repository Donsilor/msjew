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

    public function actionSyncPaypalPhone($batch='default')
    {
        $date = date('Y-m-d H:i:s');
        Console::output("Sync Start[$batch][{$date}]-------------------");
        foreach ($this->getOrder() as $item) {
            $key = "sync-pay-pal-phone:{$batch}:{$item->id}";
            \Yii::$app->cache->getOrSet($key, function () use($item) {
                $trans = \Yii::$app->db->beginTransaction();
                try {
                    \Yii::$app->services->order->syncPayPalPhone($item);
                    $trans->commit();
                    Console::output('success:'.$item->id);
                    $result = true;
                } catch (\Exception $exception) {
                    $trans->rollBack();
                    Console::output('fail:'.$item->id);
                    Console::output($exception->getMessage());
                    $result = false;
                }
                return $result;
            }, 60);
        }
        Console::output('Sync End----------------------------------------------------');
    }

    public function getOrder()
    {
        $where = ['and'];
        $where[] = [
            'order.is_tourist' => 1,
            'order_address.mobile' => ['',null],
        ];
        //$where[] = ['>', 'order_address.created_at', time()-60*60];//60分钟内的订单
        $where1 = ['>', 'order.id', 0];//id偏移量

        while ($list = Order::find()
            ->where(array_merge($where, [$where1]))
            ->select(['order.id','order.member_id','order.order_sn'])
            ->joinWith('address')
            ->innerJoin('common_pay_log', $on='order.order_sn=common_pay_log.order_sn and common_pay_log.pay_status=1 and common_pay_log.pay_type=6')
            ->limit(10)
            ->orderBy('id ASC')
            ->all()) {
            foreach ($list as $item) {
                yield $item;
            }
            $where1 = ['>', 'order.id', $item->id];
        }
        return null;
    }
}