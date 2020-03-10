<?php

namespace common\enums;

/**
 * Class InvoiceTypeEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class InvoiceTypeEnum extends BaseEnum
{
    const ENTERPRISE = 1;
    const PERSONAL = 2;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::ENTERPRISE => '企业',
            self::PERSONAL => '个人',
        ];
    }
}