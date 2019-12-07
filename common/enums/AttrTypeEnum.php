<?php

namespace common\enums;

/**
 * 属性类型枚举
 * 分类类型(1-基础属性,2-销售属性,3-定制属性)
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class AttrTypeEnum extends BaseEnum
{
  const TYPE_BASE = 1;
  const TYPE_SALE = 2;
  const TYPE_MADE = 3;
  const TYPE_CATE = 4;
  const TYPE_SERVER = 5;
    
  /**
   * @return array
   */
  public static function getMap(): array
  {
    return [
        self::TYPE_BASE => '基础属性',
        self::TYPE_SALE => '销售属性',
        self::TYPE_MADE => '定制属性',
        self::TYPE_CATE => '款式分类',
        self::TYPE_SERVER => '物流及售后',
    ];
  }
}