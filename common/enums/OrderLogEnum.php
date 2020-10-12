<?php

namespace common\enums;


use common\models\order\Order;
use common\models\order\OrderAccount;
use common\models\order\OrderAddress;
use common\models\order\OrderInvoice;
use common\models\order\OrderInvoiceEle;

class OrderLogEnum extends BaseEnum
{
    const ORDER_UNPAID = 10;
    const ORDER_PAID = 20;
    const ORDER_CONFIRM = 30;
    const ORDER_SEND = 40;
    const ORDER_FINISH = 50;
    const ORDER_CANCEL = 0;

    //动作名
    public static function actionName()
    {
        return [
            'WIRETRANSFERAUDIT'=>'电汇审核',
            'WIRETRANSFER'=>'客户提交电汇支付',
            'CREATE' => '创建订单',
            'REFUND' => '订单退款',
            'CREATE_TOURIST' => '创建游客订单',
            'SYNC_TOURIST' => '同步游客订单',
            'AUDIT' => '订单审核',
            'PAY' => '订单付款',
            'DELIVER' => '订单发货',
            'FINISH' => '订单完成',
            'CANCEL' => '订单取消',
            'FOLLOWER' => '订单跟进',
            'ELEINVOICEEDIT' => '电子发票编辑',
            'ELEINVOICESEND' => '电子发票发送',
            'CHANGEADDRESS' => '订单收件人信息修改',
            'SENDPAIDEMAIL' => '发送付款邮件',
            'SENDEXPRESSEMAIL' => '发送物流信息邮件',//sendExpressEmail
        ];
    }

    //字段名
    public static function fieldName()
    {
        static $orderFields = [];

        if(empty($orderFields)) {
            $orderFields += (new Order())->attributeLabels();
            $orderFields += (new OrderAccount())->attributeLabels();
            $orderFields += (new OrderAddress())->attributeLabels();
            $orderFields += (new OrderInvoice())->attributeLabels();
            $orderFields += (new OrderInvoiceEle())->attributeLabels();
        }

        return $orderFields;
    }

    public static function getMap(): array
    {
        return [
            self::ORDER_UNPAID => '待付款',
            self::ORDER_PAID => '已付款',
            self::ORDER_CONFIRM => '待发货',
            self::ORDER_SEND => '已发货',
            self::ORDER_FINISH => '已完成',
            self::ORDER_CANCEL => '已取消',
        ];
    }
}