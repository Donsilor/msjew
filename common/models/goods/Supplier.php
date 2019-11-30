<?php

namespace common\models\goods;

use Yii;

/**
 * This is the model class for table "goods_supplier".
 *
 * @property string $id 主键
 * @property string $supplier_code 供应商编码
 * @property int $status 状态(-1删除,0-禁用,1-正常)
 * @property int $created_at 创建时间
 * @property int $updated_at
 */
class Supplier extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'goods_supplier';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['supplier_code'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('supplier', 'ID'),
            'supplier_code' => Yii::t('supplier', 'Supplier Code'),
            'status' => Yii::t('supplier', 'Status'),
            'created_at' => Yii::t('supplier', 'Created At'),
            'updated_at' => Yii::t('supplier', 'Updated At'),
        ];
    }
}
