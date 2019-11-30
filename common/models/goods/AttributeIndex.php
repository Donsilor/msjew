<?php

namespace common\models\goods;

use Yii;

/**
 * This is the model class for table "goods_attribute_index".
 *
 * @property int $goods_id 商品id
 * @property int $cat_id 分类id
 * @property int $attr_value_id 属性值id
 * @property int $style_id 商品公共表id
 * @property int $type_id 类型id
 * @property int $attr_id 属性id
 */
class AttributeIndex extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'goods_attribute_index';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['goods_id', 'cat_id', 'attr_value_id', 'style_id', 'type_id', 'attr_id'], 'required'],
            [['goods_id', 'cat_id', 'attr_value_id', 'style_id', 'type_id', 'attr_id'], 'integer'],
            [['goods_id', 'cat_id', 'attr_value_id'], 'unique', 'targetAttribute' => ['goods_id', 'cat_id', 'attr_value_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'goods_id' => Yii::t('goods', 'Goods ID'),
            'cat_id' => Yii::t('goods', 'Cat ID'),
            'attr_value_id' => Yii::t('goods', 'Attr Value ID'),
            'style_id' => Yii::t('goods', 'Style ID'),
            'type_id' => Yii::t('goods', 'Type ID'),
            'attr_id' => Yii::t('goods', 'Attr ID'),
        ];
    }
}
