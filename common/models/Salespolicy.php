<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "goods_salespolicy".
 *
 * @property int $id ID
 * @property int $style_id
 * @property int $area_id
 * @property string $sale_price 加价后销售价
 * @property double $markup_rate 加价率
 * @property string $markup_value 规定值
 * @property int $status 状态 1启用 0禁用 -1删除
 * @property int $created_at
 * @property int $updated_at
 */
class Salespolicy extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'goods_salespolicy';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['style_id'], 'required'],
            [['style_id', 'area_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['sale_price', 'markup_rate', 'markup_value'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'style_id' => 'Style ID',
            'area_id' => 'Area ID',
            'sale_price' => '加价后销售价',
            'markup_rate' => '加价率',
            'markup_value' => '规定值',
            'status' => '状态 1启用 0禁用 -1删除',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
