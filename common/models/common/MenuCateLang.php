<?php

namespace common\models\common;

use Yii;

/**
 * This is the model class for table "{{%common_menu_cate_lang}}".
 *
 * @property int $id 主键
 * @property int $master_id
 * @property string $title 标题
 * @property string $addons_name 插件名称
 */
class MenuCateLang extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_menu_cate_lang}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['master_id'], 'required'],
            [['master_id'], 'integer'],
            [['title'], 'string', 'max' => 50],
            [['addons_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'master_id' => Yii::t('common_menu_cate_lang', 'Master ID'),
            'title' => '标题',
            'addons_name' => '插件名称',
        ];
    }
}
