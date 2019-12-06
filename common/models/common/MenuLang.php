<?php

namespace common\models\common;

use Yii;

/**
 * This is the model class for table "{{%common_menu_lang}}".
 *
 * @property int $id
 * @property int $master_id
 * @property string $title 标题
 * @property string $addons_name 插件名称
 */
class MenuLang extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_menu_lang}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
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
            'id' => Yii::t('common_menu_lang', 'ID'),
            'master_id' => Yii::t('common_menu_lang', 'Master ID'),
            'title' => '标题',
            'addons_name' => '插件名称',
        ];
    }
}
