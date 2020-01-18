<?php


namespace Omnipay\Paypal\Requests;


use common\helpers\FileHelper;
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
    public function setCurrency($value)
    {
        return $this->setParameter('currency', $value);
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
        $returnUrl = $this->getReturnUrl();

        $cancelUrl = sprintf('%s%s%s', $returnUrl, (strpos($returnUrl,'?')?'&':'?'), 'success=false');
        $returnUrl = sprintf('%s%s%s', $returnUrl, (strpos($returnUrl,'?')?'&':'?'), 'success=true');

        $this->setCancelUrl($cancelUrl);
        $this->setReturnUrl($returnUrl);
    }

    /**
     * 发送数据
     * @param $data getData的返回结果
     * @return \Omnipay\Common\Message\ResponseInterface|Payment
     * @throws \Exception
     */
    public function sendData($data)
    {
        $clientId = $this->getParameter('clientId');
        $clientSecret = $this->getParameter('clientSecret');

        $subject = $this->getParameter('subject');
        $currency = $this->getParameter('currency');
        $totalAmount = $this->getParameter('totalAmount');
        $outTradeNo = $this->getParameter('outTradeNo');
        echo "totalAmount:";
        var_dump($totalAmount);
        echo "currency:";
        var_dump($currency);
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
            print_r($payment);
            $r = $payment->create($apiContext);
            print_r($r);
        } catch (\Exception $ex) {
            $logPath = \Yii::getAlias('@runtime') . "/paypal-create/" . date('Y_m_d') . '/error.txt';
            FileHelper::writeLog($logPath, $ex->getMessage());
            throw new \Exception('paypal创建订单异常~！');
        }

        // TODO: Implement sendData() method.
        return $payment;
    }
}