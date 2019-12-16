<?php

namespace common\enums;

/**
 * 搜索类型(1-多选搜索,2-范围搜索)
 *
 * Class StatusEnum
 * @package common\enums
 */
class SearchTypeEnum extends BaseEnum
{    
    const TYPE_MUlTI = 1;//多选搜索
    const TYPE_RANGE = 2;//范围搜索
    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
                self::TYPE_MUlTI => "多选搜索",
                self::TYPE_RANGE => "范围搜索",
        ];
    }
    
    
}