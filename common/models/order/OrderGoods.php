<?php

namespace common\models\order;

use Yii;

/**
 * This is the model class for table "{{%order_goods}}".
 *
 * @property int $id 订单商品表索引id
 * @property int $merchant_id 商户ID
 * @property int $order_id 订单id
 * @property int $goods_id --商品id
 * @property string $style_sn 款式编号
 * @property string $goods_sn 商品编号
 * @property int $goods_type 产品线（商品类型）
 * @property string $goods_name 商品名称
 * @property string $goods_price 商品价格
 * @property int $goods_num 商品数量
 * @property string $goods_image 商品图片
 * @property string $goods_pay_price 商品实际成交价
 * @property int $member_id 买家ID
 * @property int $promotions_id 促销活动ID（抢购ID/限时折扣ID/优惠套装ID）与goods_type搭配使用
 * @property string $goods_spec 商品规格
 * @property string $goods_attr
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class OrderGoods extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order_goods}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'order_id', 'goods_id', 'goods_type', 'goods_num', 'member_id', 'promotions_id', 'created_at', 'updated_at'], 'integer'],
            [['order_id', 'goods_id'], 'required'],
            [['goods_price', 'goods_pay_price'], 'number'],
            [['goods_attr'], 'string'],
            [['style_sn', 'goods_sn', 'goods_name'], 'string', 'max' => 50],
            [['goods_image'], 'string', 'max' => 100],
            [['goods_spec'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'merchant_id' => '商户ID',
            'order_id' => '订单id',
            'goods_id' => '--商品id',
            'style_sn' => '款式编号',
            'goods_sn' => '商品编号',
            'goods_type' => '产品线',
            'goods_name' => '商品名称',
            'goods_price' => '商品价格',
            'goods_num' => '商品数量',
            'goods_image' => '商品图片',
            'goods_pay_price' => '商品实际成交价',
            'member_id' => '买家ID',
            'promotions_id' => '促销活动ID',
            'goods_spec' => '商品规格',
            'goods_attr' => 'Goods Attr',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
    
    public function langModel()
    {
        return new OrderGoodsLang();
    }
}
