<?php

namespace common\models\goods;

use Yii;

/**
 * This is the model class for table "goods_supplier_lang".
 *
 * @property int $id 主键
 * @property int $master_id
 * @property string $language 语言类型
 * @property string $supplier_name 供应商名称
 */
class SupplierLang extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'goods_supplier_lang';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['master_id'], 'integer'],
            [['language'], 'string', 'max' => 5],
            [['supplier_name'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('supplier', 'ID'),
            'master_id' => Yii::t('supplier', 'Master ID'),
            'language' => Yii::t('supplier', 'Language'),
            'supplier_name' => Yii::t('supplier', 'Supplier Name'),
        ];
    }
}
