<?php

namespace Omnipay\Paypal;

use Omnipay\Paypal\AbstractPaypalGateway;
use Omnipay\Paypal\Requests\PageRequest;

/**
 * Class PageGateway
 * @package Omnipay\Paypal\Request
 */
class PageGateway extends AbstractPaypalGateway
{
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
     * @param array $parameters
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function purchase(array $parameters = [])
    {
        return $this->createRequest(PageRequest::class, $parameters);
    }
}