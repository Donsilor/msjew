<?php

namespace common\enums;

/**
 * 属性类型枚举
 * 分类类型(1-基础属性,2-销售属性,3-定制属性)
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class AttrUseTypeEnum extends BaseEnum
{
    const TYPE_ALL = 0;
    const TYPE_BASE = 1;
    const TYPE_SEARCH = 2;
    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [                
                self::TYPE_BASE => '基础属性',
                self::TYPE_SEARCH => '搜索属性',
                self::TYPE_ALL => '基础+搜索',
        ];
    }   
}