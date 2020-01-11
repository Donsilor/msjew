<?php


namespace Omnipay\Paypal\Requests;


use Omnipay\Common\Message\AbstractRequest;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class PageRequest extends AbstractPaypalRequest
{

    /**
     * @param $value
     * @return PageRequest
     */
    public function setSubject($value)
    {
        return $this->setParameter('subject', $value);
    }

    /**
     * @param $value
     * @return PageRequest
     */
    public function setTotalAmount($value)
    {
        return $this->setParameter('totalAmount', $value);
    }

    /**
     * @param $value
     * @return PageRequest
     */
    public function setOutTradeNo($value)
    {
        return $this->setParameter('outTradeNo', $value);
    }

    /**
     * 获取数据
     * @inheritDoc
     */
    public function getData()
    {
        $notifyUrl = $this->getReturnUrl();
        $this->setCancelUrl(sprintf('%s?success=false', $notifyUrl));
        $this->setReturnUrl(sprintf('%s?success=true', $notifyUrl));
    }

    /**
     * 发送数据
     * @param $data getData的返回结果
     * @inheritDoc
     */
    public function sendData($data)
    {
        $clientId = $this->getParameter('clientId');
        $clientSecret = $this->getParameter('clientSecret');

        $subject = $this->getParameter('subject');
        $currency = \Yii::$app->params['currency'];
        $totalAmount = $this->getParameter('totalAmount');
        $outTradeNo = $this->getParameter('outTradeNo');

        $returnUrl = $this->getParameter('returnUrl');
        $cancelUrl = $this->getParameter('cancelUrl');

        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        // URL
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($returnUrl)
            ->setCancelUrl($cancelUrl);

        //设置金额
        $amount = new Amount();
        $amount->setCurrency($currency)
            ->setTotal($totalAmount);

        $transaction = new Transaction();
        $transaction->setAmount($amount);
        $transaction->setPurchaseOrder($outTradeNo);

        $apiContext = $this->getApiContext($clientId, $clientSecret);

        try {
            $payment = new Payment();
            $payment->setIntent("order")
                ->setPayer($payer)
                ->setRedirectUrls($redirectUrls)
                ->setTransactions(array($transaction));
            $payment->create($apiContext);
        } catch (\Exception $ex) {
            // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
            //ResultPrinter::printError("Executed Payment", "Payment", null, null, $ex);
            exit(1);
        }

        // TODO: Implement sendData() method.
        return $payment;
    }
}