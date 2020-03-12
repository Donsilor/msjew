<?php


namespace Omnipay\Paypal\Requests;


use common\helpers\FileHelper;
use Omnipay\Common\Message\ResponseInterface;
use PayPal\Api\Amount;
use PayPal\Api\Capture;
use PayPal\Api\Order;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Sale;
use PayPal\Api\Transaction;

class AuthorizeRequest extends AbstractPaypalRequest
{
    //http://www.pay.com/payments/ExecutePayment.php?success=true&paymentId=PAYID-LYLOSIA0P6970991W022162T&token=EC-2ER94547ES408234F&PayerID=ZMUBN8MYV9Q5N

    public function setModel($value)
    {
        $this->setParameter('model', $value);
    }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        $clientId = $this->getParameter('clientId');
        $clientSecret = $this->getParameter('clientSecret');

        $apiContext = $this->getApiContext($clientId, $clientSecret);

        $this->setParameter('apiContext', $apiContext);

        return null;
    }

    /**
     * 获取订单信息
     * @return Payment
     */
    public function getPayment()
    {
        $this->getData();

        $model = $this->getParameter('model');
        $apiContext = $this->getParameter('apiContext');
        return Payment::get($model->transaction_id, $apiContext);
    }

    /**
     * @inheritDoc
     */
    public function sendData($data)
    {
        $model = $this->getParameter('model');

        $apiContext = $this->getParameter('apiContext');

        try {

            //CREATED。订单是使用指定的上下文创建的。
            //SAVED。订单已保存并保留。订单状态一直持续到捕获final_capture = true订单中的所有购买单位为止。
            //APPROVED。客户通过贝宝（PayPal）钱包或其他形式的客人或非品牌付款批准了付款。例如，卡，银行帐户等。
            //VOIDED。订单中的所有购买单位均作废。
            //COMPLETED。付款已授权或已为订单捕获授权付款。completed
            $payment = Payment::get($model->transaction_id, $apiContext);

            FileHelper::writeLog('paypal-result.txt', $model->order_sn. ' ' .$model->out_trade_no. ' ' .$model->transaction_id . ' ' . $payment->state);

            //state三个状态：created:创建，approved:批准，failed:失败
            if($payment->state=="failed") {
                throw new \Exception('付款失败');
            }

            //判断付款人是否授权
            //需下载状态列表到备注
            if (!$payment->getPayer()) {
                throw new \Exception('买家未付款');
            }

            //获取订单
            $order = $this->getSale($payment);

            if (!$order) {
                $this->execute($payment);
                $order = $this->getSale($payment);
            }

            //订单状态： completed, partially_refunded, pending, refunded, denied
            $result = $order->state == 'completed';

            FileHelper::writeLog('paypal-result.txt', $model->order_sn. ' ' .$model->out_trade_no. ' ' .$model->transaction_id. ' '.$order->state);

        } catch (\Exception $e) {
            //接口错误日志
            if ($e instanceof \PayPal\Exception\PayPalConnectionException) {
                $data = $e->getData();
                $str = @var_export($data, true);
                FileHelper::writeLog('paypal-result.txt', $model->order_sn. ' ' .$model->out_trade_no. ' ' .$model->transaction_id. ' '.$str);
            }
            else {
                $logPath = \Yii::getAlias('@runtime') . "/pay-logs/paypal-" . date('Y_m_d') . '/error.txt';
                FileHelper::writeLog($logPath, $model->order_sn . '-' . $e->getMessage());
            }
            $result = false;
        }

        return $result;
    }

    /**
     * @param Payment $payment
     * @return Order|null
     */
    public function getOrder($payment)
    {
        $transactions = $payment->getTransactions();
        $transaction = $transactions[0];
        $relatedResources = $transaction->getRelatedResources();
        if (empty($relatedResources)) {
            return null;
        }
        foreach ($relatedResources as $relatedResource) {
            if($order = $relatedResource->getOrder()) {
                return $order;
            }
        }
        return null;
    }

    /**
     * @param Payment $payment
     * @return Sale|null
     */
    public function getSale($payment)
    {
        $transactions = $payment->getTransactions();
        $transaction = $transactions[0];
        $relatedResources = $transaction->getRelatedResources();
        if (empty($relatedResources)) {
            return null;
        }
        foreach ($relatedResources as $relatedResource) {
            if($order = $relatedResource->getSale()) {
                return $order;
            }
        }
        return null;
    }

    /**
     * @param Payment $payment
     * @return Capture|null
     */
    public function getCapture($payment)
    {
        $transactions = $payment->getTransactions();
        $transaction = $transactions[0];
        $relatedResources = $transaction->getRelatedResources();
        if (empty($relatedResources)) {
            return null;
        }
        foreach ($relatedResources as $relatedResource) {
            if($capture = $relatedResource->getCapture()) {
                return $capture;
            }
        }
        return null;
    }

    /**
     * @param Payment $payment
     */
    public function execute(&$payment)
    {
        $model = $this->getParameter('model');
        $apiContext = $this->getParameter('apiContext');

        $execution = new PaymentExecution();
        $execution->setPayerId($payment->getPayer()->getPayerInfo()->payer_id);

        $transaction = new Transaction();

        $amount = new Amount();
        $amount->setCurrency($model->currency)
            ->setTotal($model->total_fee);
        $transaction->setAmount($amount);

        //在执行对象中添加上述事务对象
        $execution->addTransaction($transaction);
        $payment = $payment->execute($execution, $apiContext);
    }

    /**
     * @param Order $order
     * @return mixed
     */
    public function capture($order)
    {
        $model = $this->getParameter('model');
        $apiContext = $this->getParameter('apiContext');

        //最终捕获
        $capture = new Capture();
        $capture->setIsFinalCapture(true);

        $amount = new Amount();
        $amount->setCurrency($model->currency)
            ->setTotal($model->total_fee);
        $capture->setAmount($amount);

        // ### Capture Order
        //通过传递我们创建的捕获对象来捕获订单。
        //我们将获得一个新的捕获对象。
        return $order->capture($capture, $apiContext);
    }
}