<?php

namespace common\enums;

/**
 * Class TypeEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class SeriesEnum extends BaseEnum
{
    const STYLE_CLASSIC = 1;
    const STYLE_LAYOUT = 2;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::STYLE_CLASSIC => '经典系列',
            self::STYLE_LAYOUT => '排镶系列',
        ];
    }
}