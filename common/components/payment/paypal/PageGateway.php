<?php

namespace Omnipay\Paypal;

use Omnipay\Paypal\AbstractPaypalGateway;
use Omnipay\Paypal\Requests\AuthorizeRequest;
use Omnipay\Paypal\Requests\PageRequest;

/**
 * Class PageGateway
 * @package Omnipay\Paypal\Request
 */
class PageGateway extends AbstractPaypalGateway
{

    /**
     * @param $value
     * @return mixed
     */
    public function setNotifyUrl($value)
    {
        return $this->setParameter('notifyUrl', $value);
    }

    /**
     * @param $value
     * @return mixed
     */
    public function setReturnUrl($value)
    {
        return $this->setParameter('returnUrl', $value);
    }

    /**
     * Get gateway display name
     *
     * This can be used by carts to get the display name for each gateway.
     */
    public function getName()
    {
        return 'Paypal Page Gateway';
    }

    /**
     * @param array $options
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function purchase(array $options = [])
    {
        return $this->createRequest(PageRequest::class, $options);
    }

    /**
     * 完成付款
     * @param array $options
     * @return \Omnipay\Common\Message\AbstractRequest|\Omnipay\Common\Message\RequestInterface
     */
    public function completePurchase(array $options = array())
    {
        return $this->createRequest(AuthorizeRequest::class, $options);
    }
}