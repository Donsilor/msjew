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
    const ORDER_CONFIRM = 30;
    const ORDER_SEND = 40;
    const ORDER_FINISH = 50;    
    
    /**
     * @return array
     */
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
    
    /**
     * @return array
     */
    public static function getRemarkMap(): array
    {
        return [
                self::ORDER_UNPAID => '待付款',
                self::ORDER_PAID => '已付款,待审核',
                self::ORDER_CONFIRM => '已审核,待发货',
                self::ORDER_SEND => '已发货,待收货',
                self::ORDER_FINISH => '已收货,已完成',
                self::ORDER_CANCEL => '已取消',
        ];
    }
}