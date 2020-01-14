<?php

namespace common\enums;

/**
 * 订单状态
 * Class OrderStatusEnum
 * @package common\enums *
 */
class DeliveryStatusEnum extends BaseEnum
{    
    const UNSEND = 0;
    const SEND = 1;
    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
                self::UNSEND => '待发货',
                self::SEND => '已发货',             
        ];
    }
    
}