<?php


namespace api\modules\web\forms;


use common\models\order\Order;
use common\models\pay\WireTransfer;

class WireTransferForm extends WireTransfer
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'account', 'payment_voucher'], 'required'],
            [['order_id'], 'integer'],
            ['order_id', 'validateOrderId'],
            [['account', 'payment_serial_number'], 'string', 'max' => 50],
            ['account', 'validateAccount'],
            [['payment_voucher'], 'string', 'max' => 255],
            ['payment_voucher', 'url'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'order_id' => '订单ID',
            'member_id' => '订单ID',
            'account' => '收款账号',
            'payment_serial_number' => '付款流水号',//payment_serial_number
            'payment_voucher' => '付款凭证图片',
        ];
    }

    public function validateAccount($attribute)
    {

        $configJson = \Yii::$app->debris->config('pay_collection_account_info');
        $configs = \Qiniu\json_decode($configJson, true);

        $bankInfo = [];
        foreach ($configs as $config) {
            if($config['account']==$this->account) {
                $bankInfo = $config;
            }
        }

        if (empty($bankInfo)) {
            $this->addError($attribute, '收款银行账号错误');
            return;
        }

        $this->bank_name = $bankInfo['bank_name_cn'];
        $this->account_name = $bankInfo['account_name'];
        $this->opening_bank = $bankInfo['opening_bank'];
    }

    public function validateOrderId($attribute)
    {
        if(!Order::findOne(['id'=>$this->order_id, 'member_id'=>$this->member_id])) {
            $this->addError($attribute, '订单ID错误');
        }
        if(self::findOne(['order_id'=>$this->order_id, 'member_id'=>$this->member_id])) {
            $this->addError($attribute, '电汇支付审核中');
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }
}