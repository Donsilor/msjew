<?php

namespace common\models\goods;

use Yii;

/**
 * This is the model class for table "{{%goods_attribute_value}}".
 *
 * @property int $id 主键
 * @property int $attr_id
 * @property int $attr_lang_id 属性ID
 * @property string $attr_value_name 属性值名称
 * @property string $remark 属性值描述
 * @property int $sort 属性排序(数字越小越前)
 * @property int $status 状态(-1删除,0禁用,1-正常)
 * @property int $created_at 创建时间
 * @property int $updated_at
 */
class AttributeValue extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_attribute_value}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['attr_id', 'attr_lang_id', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['attr_value_name'], 'string', 'max' => 200],
            [['remark'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('goods_attribute', 'ID'),
            'attr_id' => Yii::t('goods_attribute', 'Attr ID'),
            'attr_lang_id' => Yii::t('goods_attribute', 'Attr Lang ID'),
            'attr_value_name' => Yii::t('goods_attribute', 'Attr Value Name'),
            'remark' => Yii::t('goods_attribute', 'Remark'),
            'sort' => Yii::t('goods_attribute', 'Sort'),
            'status' => Yii::t('goods_attribute', 'Status'),
            'created_at' => Yii::t('goods_attribute', 'Created At'),
            'updated_at' => Yii::t('goods_attribute', 'Updated At'),
        ];
    }
}
