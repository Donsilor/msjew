<?php


namespace common\components\payment;


use Omnipay\Common\AbstractGateway;
use Omnipay\GlobalAlipay\WapGateway;
use Omnipay\GlobalAlipay\WebGateway;
use Omnipay\Omnipay;

class GlobalAlipayPay
{
    protected $config;

    const PC = 'GlobalAlipay_web';
    const WAP = 'GlobalAlipay_wap';

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * 实例化类
     *
     * @param string $type
     * @return AbstractGateway
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    private function create($type = self::PC)
    {
        /* @var $gateway AbstractGateway */
        $gateway = Omnipay::create($type);

        //配置
        $gateway->initialize($this->config);

        return $gateway;
    }

    /**
     * 网页支付
     * @param $order
     * @param bool $debug
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function pc($order, $debug = false)
    {
        /** @var WebGateway $gateway */
        $gateway = $this->create(self::PC);

        $request = $gateway->purchase($order);

        $response = $request->send();

        return $response->getRedirectUrl();
    }

    /**
     * 手机网站支付
     * @param $order
     * @param bool $debug
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function wap($order, $debug = false)
    {
        /** @var WapGateway $gateway */
        $gateway = $this->create(self::WAP);

        $request = $gateway->purchase($order);

        $response = $request->send();

        return $response->getRedirectUrl();
    }
}