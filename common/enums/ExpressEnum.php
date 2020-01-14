<?php

namespace common\enums;

/**
 * Class ExpressEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class ExpressEnum extends BaseEnum
{

    const POST = 1;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::POST => \Yii::t('common','中国邮政'),
        ];
    }
}