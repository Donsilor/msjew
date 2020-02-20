<?php

namespace common\models\order;

use Yii;

/**
 * This is the model class for table "{{%order_tourist_details}}".
 *
 * @property int $id 主键
 * @property int $order_tourist_id 游客订单ID
 * @property int $goods_id 商品id
 * @property int $goods_type 产品线ID
 * @property string $goods_price 商品价格
 * @property int $goods_num 购买商品数量
 * @property int $group_id 分组ID
 * @property int $group_type 分组类型 1对戒 2定制 0单品
 * @property string $goods_spec 商品规格
 */
class OrderTouristDetails extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order_tourist_details}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_tourist_id'], 'required'],
            [['order_tourist_id', 'goods_id', 'goods_type', 'goods_num', 'group_id', 'group_type'], 'integer'],
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
            'id' => Yii::t('app', '主键'),
            'order_tourist_id' => Yii::t('app', '游客订单ID'),
            'goods_id' => Yii::t('app', '商品id'),
            'goods_type' => Yii::t('app', '产品线ID'),
            'goods_price' => Yii::t('app', '商品价格'),
            'goods_num' => Yii::t('app', '购买商品数量'),
            'group_id' => Yii::t('app', '分组ID'),
            'group_type' => Yii::t('app', '分组类型 1对戒 2定制 0单品'),
            'goods_spec' => Yii::t('app', '商品规格'),
        ];
    }
}
