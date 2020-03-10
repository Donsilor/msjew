<?php

namespace common\enums;

/**
 * Class InvoiceElectronicEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class InvoiceElectronicEnum extends BaseEnum
{
    const NO = 0;
    const YES = 1;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::NO => '不是',
            self::YES => '是',
        ];
    }
}