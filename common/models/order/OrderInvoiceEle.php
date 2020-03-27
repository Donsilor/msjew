<?php

namespace common\models\order;

use common\helpers\StringHelper;
use Yii;

/**
 * This is the model class for table "order_invoice_ele".
 *
 * @property int $id
 * @property int $invoice_id
 * @property int $invoice_date 发票日期
 * @property string $sender_name 发件人
 * @property string $sender_address 发件人地址
 * @property string $shipper_name 托运人姓名
 * @property string $shipper_address 托运人地址
 * @property string $express_company_name 运输公司
 * @property string $express_no 国际空运单号
 * @property int $delivery_time 发货时间
 * @property int $updated_at 修改时间
 */
class OrderInvoiceEle extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_invoice_ele';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['invoice_id'], 'required'],
            [['invoice_id'], 'unique'],
            [['invoice_id'], 'integer'],
            [['invoice_date','language', 'delivery_time','created_at'],'safe'],
            [['sender_name', 'shipper_name'], 'string', 'max' => 50],
            [['sender_address', 'shipper_address'], 'string', 'max' => 255],
            [['express_company_name', 'express_no'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'invoice_id' => 'Invoice ID',
            'invoice_date' => '发票日期',
            'sender_name' => '发货人',
            'sender_address' => '发货人地址',
            'shipper_name' => '进口商',
            'shipper_address' => '进口商地址',
            'express_company_name' => '运输公司',
            'express_no' => '国际空运单号',
            'delivery_time' => '发货时间',
            'create_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {

        $this->invoice_date = StringHelper::dateToInt($this->invoice_date);
        $this->delivery_time = StringHelper::dateToInt($this->delivery_time);

        return parent::beforeSave($insert);
    }
}
