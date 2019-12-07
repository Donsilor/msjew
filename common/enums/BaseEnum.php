<?php

namespace common\enums;

use common\helpers\ArrayHelper;

/**
 * Class BaseEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
abstract class BaseEnum
{
    /**
     * @return array
     */
    abstract public static function getMap(): array;

    /**
     * @param $key
     * @return string
     */
    public static function getValue($key , $map = null): string
    {   
        $map = $map ?? static::getMap();
        return $map[$key] ?? '';
    }
    /**
     * @param array $keys
     * @return array
     */
    public static function getValues(array $keys, $map = null) : array
    {
        return ArrayHelper::filter($map ??static::getMap(), $keys);
    }
    /**
     * @return array
     */
    public static function getKeys($map = null): array
    {
        return array_keys($map ??static::getMap());
    }
    
    public static function getSubMap($subname)
    {
        return self::$$subname;
    }
    
}