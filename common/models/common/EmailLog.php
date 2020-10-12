<?php

namespace common\models\common;

use Yii;
use common\behaviors\MerchantBehavior;
use common\enums\OrderStatusEnum;

/**
 * This is the model class for table "{{%common_sms_log}}".
 *
 * @property int $id
 * @property string $merchant_id 商户id
 * @property string $member_id 用户id
 * @property int $platform platform
 * @property string $mobile 手机号码
 * @property string $code 验证码
 * @property string $content 内容
 * @property int $error_code 报错code
 * @property string $error_msg 报错信息
 * @property string $error_data 报错日志
 * @property string $usage 用途
 * @property int $used 是否使用[0:未使用;1:已使用]
 * @property int $use_time 使用时间
 * @property int $status 状态(-1:已删除,0:禁用,1:正常)
 * @property string $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class EmailLog extends \common\models\base\BaseModel
{
    use MerchantBehavior;
    
    const USAGE_LOGIN = 'login';
    const USAGE_REGISTER = 'register';
    const USAGE_UP_PWD = 'up-pwd';
    const USAGE_ORDER_UNPAID = 'order-unpaid';
    const USAGE_ORDER_PAID = 'order-paid';
    const USAGE_ORDER_SEND = 'order-send';
    const USAGE_ORDER_REFUND_NOTICE = 'order-refund-notice';
    const USAGE_ORDER_INVOICE = 'order-invoice';
    const USAGE_WIRE_TRANSFER_ORDER_NOTICE = 'wire-transfer-order-notice';
    const USAGE_ORDER_PAY_SUCCESS = 'order-pay-success-notice';
    const USAGE_ORDER_ABNORMAL_NOTICE = 'order-abnormal-notice';
    const USAGE_SEND_ORDER_EXPRESS_NOTICE = 'send-order-express-notice';
    /**
     * @var array
     */
    public static $usageExplain = [
            self::USAGE_LOGIN => '登录验证码',
            self::USAGE_REGISTER => '注册验证码',
            self::USAGE_UP_PWD => '重置密码验证码',
            self::USAGE_ORDER_UNPAID => '待付款订单通知',
            self::USAGE_ORDER_PAID => '已付款订单通知',
            self::USAGE_ORDER_SEND => '已发货订单通知',
            self::USAGE_ORDER_REFUND_NOTICE => '订单退款通知',
            self::USAGE_ORDER_INVOICE => '订单电子凭证',
        self::USAGE_WIRE_TRANSFER_ORDER_NOTICE => '电汇订单通知',
        self::USAGE_ORDER_PAY_SUCCESS => '付款成功通知',
        self::USAGE_ORDER_ABNORMAL_NOTICE => '订单异常通知',
        self::USAGE_SEND_ORDER_EXPRESS_NOTICE => '已发货订单通知',
    ];
    public static $usageTemplates = [
            self::USAGE_LOGIN => 'loginCode',
            self::USAGE_REGISTER => 'registerCode',
            self::USAGE_UP_PWD => 'passwordResetCode',
            self::USAGE_ORDER_UNPAID => 'orderNotification',
            self::USAGE_ORDER_PAID => 'orderNotification',
            self::USAGE_ORDER_SEND => 'orderNotification',
            self::USAGE_ORDER_REFUND_NOTICE => 'orderNotification',
            self::USAGE_ORDER_INVOICE => 'orderInvoice',
        self::USAGE_WIRE_TRANSFER_ORDER_NOTICE => 'wireTransferOrderNotice',
        self::USAGE_ORDER_PAY_SUCCESS => 'orderPaySuccessNotice',
        self::USAGE_ORDER_ABNORMAL_NOTICE => 'OrderAbnormalNotice',
        self::USAGE_SEND_ORDER_EXPRESS_NOTICE => 'sendOrderExpressNotice',
    ];
    public static $orderStatusMap = [
            OrderStatusEnum::ORDER_UNPAID =>self::USAGE_ORDER_UNPAID,
            OrderStatusEnum::ORDER_PAID =>self::USAGE_ORDER_PAID,
            OrderStatusEnum::ORDER_SEND =>self::USAGE_ORDER_SEND,
            'refund' =>self::USAGE_ORDER_REFUND_NOTICE,
    ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_email_log}}';
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
                [['merchant_id', 'member_id', 'error_code', 'used', 'email', 'code', 'use_time', 'status', 'created_at', 'updated_at', 'platform'], 'integer'],
                [['error_data'], 'string'],
                [['usage'], 'string', 'max' => 20],
                [['ip'], 'string', 'max' => 30],
                [['content'], 'string', 'max' => 500],
                [['title','error_msg'], 'string', 'max' => 300],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
                'id' => 'ID',
                'merchant_id' => '商户',
                'member_id' => '用户',
                'platform' => '平台',
                'email' => '手机号码',
                'code' => '验证码',
                'title' => '标题',
                'content' => '内容',
                'error_code' => '状态Code',
                'error_msg' => '状态说明',
                'error_data' => '具体信息',
                'usage' => '用途',
                'used' => '是否使用',
                'use_time' => '使用时间',
                'ip' => 'ip',
                'status' => '状态',
                'created_at' => '创建时间',
                'updated_at' => '修改时间',
        ];
    }
    
    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        /* if (!$this->ip) {
            $this->ip = Yii::$app->request->userIP?Yii::$app->request->userIP:$this->ip;
        } */
        
        return parent::beforeSave($insert);
    }
}
