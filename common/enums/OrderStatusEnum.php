<?php

namespace common\enums;

/**
 * 订单状态
 * Class OrderStatusEnum
 * @package common\enums * 
 */
class OrderStatusEnum extends BaseEnum
{
    const ORDER_CANCEL = 0;
    const ORDER_UNPAID = 10;
    const ORDER_PAID = 20;
    const ORDER_SEND = 30;
    const ORDER_FINISH = 40;    
    
    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [                
                self::ORDER_UNPAID => '待付款',
                self::ORDER_PAID => '已付款',
                self::ORDER_SEND => '已发货',
                self::ORDER_FINISH => '已完成',
                self::ORDER_CANCEL => '已取消',
        ];
    }
}