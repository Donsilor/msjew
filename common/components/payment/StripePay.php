<?php


namespace common\components\payment;


use DigiTickets\Stripe\CheckoutGateway;
use Omnipay\Omnipay;
use Yii;
use yii\helpers\ArrayHelper;


class StripePay
{

    const PC = "\\DigiTickets\Stripe\CheckoutGateway";

    protected $config;

    /**
     * StripePay constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * @param $type
     * @return \Omnipay\Common\GatewayInterface
     */
    private function create($type)
    {
        $gateway = Omnipay::create($type);
        $gateway->setApiKey($this->config['apiKey']);

        return $gateway;
    }

    public function pc($order, $debug = false)
    {

        $gateway = $this->create(self::PC);

        $request = $gateway->purchase($order);

        //返回URL
        $payment = $request->send();
print_r($payment);exit;
        /**
         * 直接跳转
         * return $response->redirect();
         */
        return $debug == true ? '' : $payment->getRedirectUrl();
    }
}