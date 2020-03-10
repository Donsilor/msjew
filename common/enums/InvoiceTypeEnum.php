<?php

namespace common\enums;

/**
 * Class InvoiceTypeEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class InvoiceTypeEnum extends BaseEnum
{
    const PERSONAL = 1;
    const ENTERPRISE = 2;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::PERSONAL => '个人',
            self::ENTERPRISE => '企业',
        ];
    }
}