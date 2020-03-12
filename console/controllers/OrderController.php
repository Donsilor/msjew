<?php

namespace console\controllers;

use yii\console\Controller;
use common\enums\OrderStatusEnum;
use common\models\order\Order;

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
}