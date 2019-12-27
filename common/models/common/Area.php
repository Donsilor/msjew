<?php

namespace common\models\common;

use Yii;

/**
 * This is the model class for table "{{%common_area}}".
 *
 * @property int $id 主键
 * @property int $pid 父ID
 * @property string $path 路径
 * @property int $level 层级
 * @property string $name_zh_cn 中文名称
 * @property string $name_zh_tw 繁体名称
 * @property string $name_en_us 英文名称
 * @property string $name_pinyin 中文拼音
 * @property string $code 代码
 * @property int $sort 排序
 */
class Area extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_area}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pid', 'level', 'sort'], 'integer'],
            [['path', 'name_zh_cn', 'name_zh_tw', 'name_en_us', 'name_pinyin'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'pid' => '父ID',
            'path' => '路径',
            'level' => '层级',
            'name_zh_cn' => '中文名称',
            'name_zh_tw' => '繁体名称',
            'name_en_us' => '英文名称',
            'name_pinyin' => '中文拼音',
            'code' => '代码',
            'sort' => '排序',
        ];
    }
}
