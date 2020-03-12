<?php

namespace Omnipay\Paydollar\Message;

use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Paydollar\Helper;

/**
 * Class ClientPurchaseRequest
 * @package Omnipay\Paydollar\Message
 */
class ClientQueryRequest extends AbstractClientRequest
{
    protected $sandbox_pay_server_url = 'https://test.paydollar.com/b2cDemo/eng/merchant/api/orderApi.jsp';
    protected $pay_server_url = 'https://www.paydollar.com/b2c2/eng/merchant/api/orderApi.jsp';

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $this->validateData();

        $data = array (
            'merchantId'     => $this->getMerchantId(),
            'loginId'        => $this->getLoginId(),
            'password'       => $this->getPassword(),
            'actionType'     => 'Query',
            'orderRef'       => $this->getOrderRef(),


        );

        $data = Helper::filterData($data);

        return $data;
    }


    private function validateData()
    {
        $this->validate(
            'merchantId',
            'loginId',
            'password',
//            'actionType',
            'orderRef'
        );
    }


    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     *
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        $xmlString = Helper::sendQueryRequest($this->getPayServerUrl(), $data);
        $result = @json_decode(json_encode(simplexml_load_string($xmlString, 'SimpleXMLElement', LIBXML_NOCDATA)), true);

        return $this->response = new ClientQueryResponse($this, empty($result['record'])?[]:$result['record']);
    }
}
