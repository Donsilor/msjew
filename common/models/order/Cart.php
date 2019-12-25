<?php

namespace common\models\order;

use Yii;

/**
 * This is the model class for table "{{%order_cart}}".
 *
 * @property int $id 购物车id
 * @property int $merchant_id 商户ID
 * @property int $buyer_id 买家id
 * @property int $store_id 店铺id
 * @property int $style_id 款号ID
 * @property int $goods_id 商品id
 * @property int $goods_type 产品线ID
 * @property string $goods_price 商品价格
 * @property int $goods_num 购买商品数量
 * @property int $group_id 分组ID
 * @property int $group_type 分组类型
 * @property string $goods_spec 商品规格
 */
class Cart extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order_cart}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'buyer_id', 'store_id', 'style_id', 'goods_id', 'goods_type', 'goods_num', 'group_id', 'group_type'], 'integer'],
            [['goods_price'], 'number'],
            [['goods_spec'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '购物车id',
            'merchant_id' => '商户ID',
            'buyer_id' => '买家id',
            'store_id' => '店铺id',
            'style_id' => '款号ID',
            'goods_id' => '商品id',
            'goods_type' => '产品线ID',
            'goods_price' => '商品价格',
            'goods_num' => '购买商品数量',
            'group_id' => '分组ID',
            'group_type' => '分组类型',
            'goods_spec' => '商品规格',
        ];
    }
}
