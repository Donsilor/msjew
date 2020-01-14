<?php


namespace common\components\payment;


use Omnipay\Omnipay;

class GlobalAlipayPay
{
    protected $config;

    const PC = 'Paypal_Page';

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * 实例化类
     *
     * @param string $type
     * @return \Omnipay\Alipay\AopPageGateway
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    private function create($type = self::PC)
    {
        /* @var $gateway \Omnipay\Alipay\AopPageGateway */
        $gateway = Omnipay::create($type);
        $gateway->setSignType('RSA2'); // RSA/RSA2/MD5
        $gateway->setAppId($this->config['app_id']);
        $gateway->setAlipayPublicKey(Yii::getAlias($this->config['ali_public_key']));
        $gateway->setPrivateKey(Yii::getAlias($this->config['private_key']));
        $gateway->setNotifyUrl($this->config['notify_url']);
        !empty($this->config['return_url']) && $gateway->setReturnUrl($this->config['return_url']);
        $this->config['sandbox'] === true && $gateway->sandbox();

        return $gateway;
    }
}