<?php

namespace common\enums;

/**
 * Class AppEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class OrderFromEnum extends BaseEnum
{
    const FROM_WEB = 1;
    const FROM_MOBILE = 2;
    
    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
                self::FROM_WEB => 'PC端下单',
                self::FROM_MOBILE => '手机端下单',                
        ];
    }
}