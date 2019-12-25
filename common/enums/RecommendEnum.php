<?php

namespace common\enums;

/**
 * 状态枚举
 *
 * Class StatusEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class RecommendEnum extends BaseEnum
{
    const RECOMMEND_HOME = 1;
    const RECOMMEND_COMPLE = 2;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::RECOMMEND_HOME => '首页',
            self::RECOMMEND_COMPLE => '综合页',
        ];
    }
}