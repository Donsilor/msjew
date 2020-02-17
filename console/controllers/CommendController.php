<?php

namespace console\controllers;

use yii\console\Controller;
use common\enums\OrderStatusEnum;
use common\models\order\Order;

/**
 * 命令行任务处理
 * Class CommendController
 * @package console\controllers
 */
class CommendController extends Controller
{
    /**
     * 订单超时自动取消
     * @param number $minute
     */
    public function actionOrderTimeoutCancel()
    {
        echo 'start------'.PHP_EOL;
        $order_total = 0;
        for($page = 1 ; $page <= 100; $page ++) {
            $order_list = Order::find()->select(['id'])
                ->where(['order_status'=>OrderStatusEnum::ORDER_UNPAID])
                ->andWhere(['>','created_at',time() + 1*60*3600])
                ->limit(100)->all();
            
            foreach ($order_list as $order) {
                $order_total++;
                \Yii::$app->services->order->changeOrderStatusCancel($order->id, '超期未支付系统自动关闭订单', 'system','system');
            }
        }
        echo 'order_total:'.$order_total.PHP_EOL;
        echo 'end------'.PHP_EOL;
    }
}