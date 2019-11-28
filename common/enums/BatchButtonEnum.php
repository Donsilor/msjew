<?php

namespace common\enums;

/**
 * 列表批量操作按钮
 *
 * Class StatusEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class BatchButtonEnum extends BaseEnum
{
    const BUTTON_STATUS_ENABLE = 1;//批量启用
    const BUTTON_STATUS_DISABLE = 2;//下拉框
    const BUTTON_EXPORT = 3;//导出
    const BUTTON_STATUS_DELETE = 4;//软删除
    const BUTTON_DELETE = 5;//物理删除
    
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