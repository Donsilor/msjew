<?php

namespace common\enums;

/**
 * 地区枚举
 * AreaEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class AreaEnum extends BaseEnum
{
    const China = 1;
    const HongKong = 2;
    const MaCao = 3;
    const Other = 99;
    
    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
                self::China => '中国',
                self::HongKong => '香港',
                self::MaCao => '澳门',
                self::Other => '国外',
        ];
    }  
    
}