<?php

namespace common\components\payment;

use Yii;
use Omnipay\Omnipay;
use Omnipay\Paypal\Responses\AopTradeAppPayResponse;
use Omnipay\Paypal\Responses\AopTradePreCreateResponse;
use Omnipay\Paypal\Responses\AopTradeWapPayResponse;

/**
 * Class PaypalPay
 * @package common\components\payment
 */
class PaypalPay
{
    protected $config;

    const PC = 'Paypal_Page';
    const APP = 'Paypal_App';
    const F2F = 'Paypal_F2F';
    const WAP = 'Paypal_Wap';

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * 实例化类
     *
     * @param string $type
     * @return \Omnipay\Paypal\AbstractPaypalGateway
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    private function create($type = self::PC)
    {
        /* @var $gateway \Omnipay\Paypal\PageGateway */
        $gateway = Omnipay::create($type);

        //配置
        $gateway->initialize($this->config);

        return $gateway;
    }

    /**
     * 电脑网站支付
     *
     * @param $config
     *
     * 参数说明
     * $config = [
     *     'subject'      => 'test',
     *     'out_trade_no' => date('YmdHis') . mt_rand(1000, 9999),
     *     'total_amount' => '0.01',
     * ]
     *
     * @return string
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function pc($order, $debug = false)
    {
        $gateway = $this->create(self::PC);

        $request = $gateway->purchase($order);

        $request->setCancelUrl('http://www.pay.com/payments/OrderAuthorize.php?success=false');
        $request->setReturnUrl('http://www.pay.com/payments/OrderAuthorize.php?success=true');

        //返回URL
        $payment = $request->send();

        /**
         * 直接跳转
         * return $response->redirect();
         */
        return $debug == true ? '' : $payment->getApprovalLink();
    }

    /**
     * 退款
     *
     * $info = [
     *     'out_trade_no' => 'The existing Order ID',
     *     'trade_no' => 'The Transaction ID received in the previous request',
     *     'refund_amount' => 18.4,
     *     'out_request_no' => date('YmdHis') . mt_rand(1000, 9999)
     *  ]
     *
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function refund(array $info)
    {
        $gateway = $this->create();
        /**
         * 退款
         */
        $request = $gateway->refund();

        $response = $request->setBizContent($info)->send();

        return $response->getData();
    }

    /**
     * 异步/同步通知
     *
     * @return \Omnipay\Paypal\Requests\CompletePurchaseRequest
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function notify()
    {
        $gateway = $this->create();
        /**
         * 退知成功
         */
        $request = $gateway->completePurchase();
        $request->setParams(array_merge(Yii::$app->request->post(), Yii::$app->request->get())); // Optional

        return $request;
    }
}
