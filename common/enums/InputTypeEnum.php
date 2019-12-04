<?php

namespace common\enums;

/**
 * 属性输入框类型(1-输入框,2-下拉框,3-单选,4-多选)
 *
 * Class StatusEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class InputTypeEnum extends BaseEnum
{
    const INPUT_TEXT = 1;//文本框
    const INPUT_SELECT = 2;//下拉框
    const INPUT_RADIO = 3;//单选框
    const INPUT_MUlTI = 4;//多选    
    
    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
                self::INPUT_TEXT => "文本框",
                self::INPUT_SELECT => "下拉框",
                self::INPUT_RADIO => "单选框",
                self::INPUT_MUlTI => "多选框",
        ];
    }   
    
}