<?php

namespace common\enums;

/**
 * 订单状态
 * Class OrderStatusEnum
 * @package common\enums * 
 */
class OrderTouristStatusEnum extends BaseEnum
{    
    const ORDER_UNPAID = 0;
    const ORDER_PAID = 1;
    const ORDER_SYNC = 2;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [                
                self::ORDER_UNPAID => '待付款',
                self::ORDER_PAID => '已付款',
                self::ORDER_SYNC => '待发货',
        ];
    }   
    
}