<?php

namespace common\enums;

/**
 * 是否现货
 * 分类类型(1-基础属性,2-销售属性,3-定制属性)
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class IsStockEnum extends BaseEnum
{
    const YES = 1;
    const NO = 0;
    
    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
                self::YES => '现货',
                self::NO => '期货',
        ];
    }
}