<?php

namespace common\enums;

/**
 * 跟进状态枚举
 *
 * Class FollowStatusEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class FollowStatusEnum extends BaseEnum
{
    const YES = 1;
    const NO = 0;
    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
                self::NO => '未跟进',
                self::YES => '已跟进',
        ];
    }
}