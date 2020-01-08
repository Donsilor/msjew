<?php

namespace common\enums;

/**
 * 属性类型枚举
 * 分类类型(1-基础属性,2-销售属性,3-定制属性,4款式分类)
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
                self::Other => '其他',
        ];
    }    
    
}