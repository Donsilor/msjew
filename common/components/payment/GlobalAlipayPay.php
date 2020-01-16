<?php


namespace common\components\payment;


use Omnipay\Common\AbstractGateway;
use Omnipay\GlobalAlipay\WapGateway;
use Omnipay\GlobalAlipay\WebGateway;
use Omnipay\Omnipay;

class GlobalAlipayPay
{
    protected $config;

    const PC = 'GlobalAlipay_Web';
    const WAP = 'GlobalAlipay_Wap';

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

    public function notify($params=[])
    {
        /** @var WebGateway $gateway */
        $gateway = $this->create(self::PC);

        $params = [
            'request_params' => array_merge($_GET, $_POST, $params), //Don't use $_REQUEST for may contain $_COOKIE
        ];

        $response = $gateway->completePurchase($params)->send();

        return $response;
    }

    /**
     * 验证支付是否成功
     * @param array $info
     * @return bool
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function verify($info=[])
    {
        unset($info['orderId']);
        unset($info['model']);
        $gateway = $this->create();
        $response = $gateway->completePurchase($info)->send();
        return $response->isPaid();
    }
}