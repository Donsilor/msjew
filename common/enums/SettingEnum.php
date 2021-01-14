<?php
/**
 * Created by PhpStorm.
 * User: BDD
 * Date: 2019/11/25
 * Time: 14:07
 */
namespace common\enums;

class SettingEnum
{
    const SET_ADV_TYPE_WEB = 1;
    const SET_ADV_TYPE_MOBILE = 2;

    const SET_SHOW_TYPE_ONE = 1;
    const SET_SHOW_TYPE_MORE = 2;

    const SET_OPEN_TYPE_YES = 1;
    const SET_OPEN_TYPE_NO = 0;

    /**
     * @return array
     */
    public static $advTypeAction = [
        self::SET_ADV_TYPE_WEB => 'WEB端',
        self::SET_ADV_TYPE_MOBILE=> '移动端',
    ];



    public static $showTypeAction = [
        self::SET_SHOW_TYPE_MORE=> '发布多条并幻灯展示',
        self::SET_SHOW_TYPE_ONE => '只发布并展示一条广告',

    ];
    public static $showTypeActionSimple = [
        self::SET_SHOW_TYPE_MORE=> '多条',
        self::SET_SHOW_TYPE_ONE => '一条',

    ];


    public static $openTypeAction = [
        self::SET_OPEN_TYPE_YES => '是',
        self::SET_OPEN_TYPE_NO => '否',
    ];
}






