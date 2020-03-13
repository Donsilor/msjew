<?php

namespace common\enums;

/**
 * Class ExpressEnum
 * @package common\enums
 * 1、顺丰
2、FEDEX
3、DHL
 */
class ExpressEnum extends BaseEnum
{

    const Shunfeng = 1;
    const FEDEX = 2;
    const DHL = 3;
  /**
   * 
   */
    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::Shunfeng => \Yii::t('common','顺丰快递'),
            self::FEDEX => \Yii::t('common','FEDEX'),
            self::DHL => \Yii::t('common','DHL'),
        ];
    }
}