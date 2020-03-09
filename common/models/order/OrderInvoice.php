<?php

namespace common\models\order;

use common\helpers\RegularHelper;
use Yii;

/**
 * This is the model class for table "order_invoice".
 *
 * @property int $id
 * @property int $order_id 订单ID
 * @property int $invoice_type 发票类型：1=企业，2=个人
 * @property string $invoice_title 发票抬头
 * @property string $tax_number 纳税人识别号
 * @property int $is_electronic 是否电子发票：0=不是，1=是
 * @property string $email 接收电子发票的邮箱
 */
class OrderInvoice extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{order_invoice}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id'], 'required'],
            [['order_id', 'invoice_type', 'is_electronic'], 'integer'],
            [['invoice_title'], 'string', 'max' => 80],
            [['tax_number'], 'string', 'max' => 50],
            [['invoice_title','tax_number'], 'safe'],
            [['email'], 'string', 'max' => 60],
            ['email', 'match', 'pattern' => RegularHelper::email(), 'message' => '请输入正确的发票接收邮箱'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => '订单ID',
            'invoice_type' => '发票类型',
            'invoice_title' => '发票抬头',
            'tax_number' => '纳税人识别号',
            'is_electronic' => '是否电子发票',
            'email' => '接收邮箱',
        ];
    }
}
