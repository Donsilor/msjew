<?php

namespace common\models\forms;

use common\models\order\OrderTourist;
use Yii;
use yii\base\Model;
use yii\web\UnprocessableEntityHttpException;
use common\enums\PayEnum;
use common\enums\OrderStatusEnum;
use common\models\order\Order;

/**
 * Class PayForm
 * @package api\modules\v1\forms
 * @author jianyan74 <751393839@qq.com>
 */
class PayForm extends Model
{
    public $orderGroup = 'default';
    public $payType;
    public $tradeType = 'default';
    public $data; // json数组
    public $memberId;
    public $returnUrl;
    public $notifyUrl;
    public $orderId;
    public $coinType;
    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['orderGroup', 'payType', 'tradeType', 'memberId','coinType'], 'required'],
            [['orderGroup'], 'in', 'range' => array_keys(PayEnum::$orderGroupExplain)],
            [['payType'], 'in', 'range' => array_keys(PayEnum::$payTypeExplain)],
            [['notifyUrl', 'returnUrl','coinType'], 'string'],
            [['tradeType'], 'verifyTradeType'],
            [['orderId'],'integer']
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'orderGroup' => '订单组别',
            'data' => '组别对应数据',
            'payType' => '支付类别',
            'tradeType' => '交易类别',
            'memberId' => '用户id',
            'returnUrl' => '跳转地址',
            'notifyUrl' => '回调地址',
            'coinType' => '货币',
        ];
    }

    /**
     * 校验交易类型
     */
    public function verifyTradeType($attribute)
    {
        switch ($this->payType) {
            case PayEnum::PAY_TYPE :
                break;
            case PayEnum::PAY_TYPE_WECHAT :
                if (!in_array($this->tradeType, ['native', 'app', 'js', 'pos', 'mweb'])) {
                    $this->addError($attribute, '微信交易类型不符');
                }
                break;
            case PayEnum::PAY_TYPE_ALI :
                if (!in_array($this->tradeType, ['pc', 'app', 'f2f', 'wap'])) {
                    $this->addError($attribute, '支付宝交易类型不符');
                }
                break;
            case PayEnum::PAY_TYPE_MINI_PROGRAM :
                break;
            case PayEnum::PAY_TYPE_UNION :
                if (!in_array($this->tradeType, ['app', 'html'])) {
                    $this->addError($attribute, '银联交易类型不符');
                }
                break;
            case PayEnum::PAY_TYPE_PAYPAL :
                if (!in_array($this->tradeType, ['pc', 'wap'])) {
                    $this->addError($attribute, 'PayPal交易类型不符');
                }
                if($this->coinType == 'CNY') {
                    $this->addError($attribute, \Yii::t('payment', 'NOT_SUPPORT_PAYPAL'));
                }
                break;
            case PayEnum::PAY_TYPE_GLOBAL_ALIPAY :
                if (!in_array($this->tradeType, ['pc', 'wap'])) {
                    $this->addError($attribute, 'GlobalAlipay交易类型不符');
                }
                break;
        }
    }

    /**
     * @return array
     * @throws UnprocessableEntityHttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \yii\base\InvalidConfigException
     */
    public function getConfig()
    {
        $action = PayEnum::$payTypeAction[$this->payType];
        $baseOrder = $this->getBaseOrderInfo();

        return Yii::$app->services->pay->$action($this, $baseOrder);
    }

    /**
     * 获取支付基础信息
     *
     * @param $type
     * @param $data
     * @return array
     */
    protected function getBaseOrderInfo()
    {
        //$data = $this->data;        
        switch ($this->orderGroup) {
            case PayEnum::ORDER_GROUP :

                $order = Order::find()->where(['id'=>$this->orderId,'member_id'=>$this->memberId])->one();
                if(empty($order) || $order->order_status != OrderStatusEnum::ORDER_UNPAID) {
                    throw new UnprocessableEntityHttpException("支付失败,订单状态已变更");
                }
                // TODO 查询订单获取订单信息
                $orderSn = $order->order_sn;
                $totalFee = $order->account->order_amount - $order->account->discount_amount;
                $currency = $order->account->currency;
                $exchangeRate = $order->account->exchange_rate;
                
                Order::updateAll(['payment_type'=>$this->payType],['id'=>$order->id]);//更改订单支付方式
                
                $order = [
                    'body' => "商品",
                    'total_fee' => $totalFee,
                    'currency' => $currency,
                    'exchange_rate'=>$exchangeRate
                ];
                break;
            case PayEnum::ORDER_TOURIST :
                // 游客订单支付
                $order = OrderTourist::find()->where(['id'=>$this->orderId])->one();

                $orderSn = $order->order_sn;
                $totalFee = $order->order_amount - $order->discount_amount;
                $currency = $order->currency;
                $exchangeRate = $order->exchange_rate;
                $order = [
                    'body' => "商品",
                    'total_fee' => $totalFee,
                    'currency' => $currency,
                    'exchange_rate' => $exchangeRate
                ];
                break;
            case PayEnum::ORDER_GROUP_GOODS :
                // TODO 查询充值生成充值订单
                $orderSn = '';
                $totalFee = '';
                $order = [
                    'body' => '',
                    'total_fee' => $totalFee,
                ];
                break;
        }

        // 也可直接查数据库对应的关联ID，这样子一个订单只生成一个支付操作ID 增加下单率
        // Yii::$app->services->pay->findByOutTradeNo($order->out_trade_no);

        $order['out_trade_no'] = Yii::$app->services->pay->getOutTradeNo(  
            $totalFee,
            $orderSn,
            $this->payType,
            $this->tradeType,
            $this->orderGroup,
            $currency,
            $exchangeRate
        );

        // 必须返回 body、total_fee、out_trade_no
        return $order;
    }
}