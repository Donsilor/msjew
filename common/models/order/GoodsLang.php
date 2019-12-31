<?php

namespace common\models\order;

use Yii;

/**
 * This is the model class for table "{{%order_goods_lang}}".
 *
 * @property int $id 订单商品表索引id
 * @property int $master_id 订单id
 * @property string $language
 * @property string $goods_name 商品名称
 * @property string $goods_spec 商品规格
 * @property string $goods_attr 商品属性
 * @property string $goods_body 商品详情
 */
class GoodsLang extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order_goods_lang}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['master_id', 'goods_name'], 'required'],
            [['master_id'], 'integer'],
            [['goods_attr'], 'string'],
            [['language'], 'string', 'max' => 5],
            [['goods_name'], 'string', 'max' => 50],
            [['goods_spec'], 'string', 'max' => 1024],
            [['goods_body'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '订单商品表索引id',
            'master_id' => '订单id',
            'language' => 'Language',
            'goods_name' => '商品名称',
            'goods_spec' => '商品规格',
            'goods_attr' => '商品属性',
            'goods_body' => '商品详情',
        ];
    }
}
