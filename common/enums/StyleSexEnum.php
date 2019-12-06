<?php

namespace common\enums;

/**
 * Class TypeEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class StyleSexEnum extends BaseEnum
{
    const MAN = 1;
    const WOMEN = 2;
    const COMMON = 3;
    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
                self::MAN => '男款',
                self::WOMEN => '女款',
                self::COMMON => '中性款',
        ];
    }
}