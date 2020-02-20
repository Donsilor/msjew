<?php

namespace common\models\goods;

use Yii;
use common\models\base\BaseModel;

/**
 * This is the model class for table "goods_markup".
 *
 * @property int $goods_id 商品ID
 * @property int $area_id 地区ID
 * @property int $markup_id 加价率ID
 * @property string $sale_price 商品销售价
 * @property int $status 状态 1启用 0禁用 -1删除
 * @property int $created_at
 * @property int $updated_at
 */
class GoodsMarkup extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'goods_markup';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['goods_id', 'area_id'], 'required'],
            [['goods_id', 'area_id', 'markup_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['sale_price'], 'number'],
            [['goods_id', 'area_id'], 'unique', 'targetAttribute' => ['goods_id', 'area_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'goods_id' => '商品ID',
            'area_id' => '地区ID',
            'markup_id' => '加价率ID',
            'sale_price' => '商品销售价',
            'status' => '状态 1启用 0禁用 -1删除',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
