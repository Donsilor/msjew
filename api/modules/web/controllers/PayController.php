<?php

namespace api\modules\web\controllers;

use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\helpers\FileHelper;
use common\models\common\PayLog;
use Yii;
use api\controllers\OnAuthController;
use common\enums\PayEnum;
use common\helpers\Url;
use common\models\forms\PayForm;
use common\helpers\ResultHelper;
use yii\db\Exception;
use yii\helpers\Json;
use yii\web\UnprocessableEntityHttpException;
use function GuzzleHttp\Psr7\parse_query;

/**
 * 公用支付生成
 *
 * Class PayController
 * @package api\modules\v1\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class PayController extends OnAuthController
{
    protected $authOptional = ['verify'];

    /**
     * @var PayForm
     */
    public $modelClass = PayForm::class;

    /**
     * 生成支付参数
     *
     * @return array|mixed|\yii\db\ActiveRecord
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function actionCreate()
    {
        $data = Yii::$app->request->post();

        //支付宝，非人民币业务使用国际版
        if(!empty($data['payType']) && $data['payType'] == PayEnum::PAY_TYPE_ALI && Yii::$app->params['currency'] != 'CNY') {
            $data['payType'] = PayEnum::PAY_TYPE_GLOBAL_ALIPAY;
        }

        /* @var $model PayForm */
        $model = new $this->modelClass();
        $model->attributes = $data;
        $model->memberId = $this->member_id;
         
        if (isset(PayEnum::$payTypeAction[$model->payType])) {
            $model->notifyUrl = Url::removeMerchantIdUrl('toFront', ['notify/' . PayEnum::$payTypeAction[$model->payType]]);
        }
        if (!$model->validate()) {
            return ResultHelper::api(422, $this->getError($model));
        }
        return $model->getConfig();
    }

    /**
     * 通过回跳URL参数，查找支付记录
     * @param $query
     * @return array|\yii\db\ActiveRecord|null
     */
    private function getPayModelByReturnUrlQuery($query)
    {
        $where = [];

        //paypal
        if(!empty($query['paymentId'])) {
            $where['transaction_id'] = $query['paymentId'];
        }

        //alipay
        if(!empty($query['out_trade_no'])) {
            $where['out_trade_no'] = $query['out_trade_no'];
        }


        if(!empty($where) && ($model = PayLog::find()->where($where)->one())) {
            return $model;
        }

        return null;
    }

    /**
     * 无登录验证
     * @return array
     */
    public function actionVerify()
    {
        //返回结果
        $result = [
            'verification_status' => 'false'
        ];

        //获取操作实例
        $returnUrl = Yii::$app->request->post('return_url', null);

        try {
            $urlInfo = parse_url($returnUrl);
            $query = parse_query($urlInfo['query']);

            //获取支付记录模型
            $model = $this->getPayModelByReturnUrlQuery($query);

            if(empty($model)) {
                throw new \Exception('数据异常');
            }

            //获取支付类
            $pay = Yii::$app->services->pay->getPayByType($model->pay_type);

            //验证是否支付
            $notify = $pay->verify(array_merge($query, ['model'=>$model]));

            //验证重试一次
            if(!$notify) {
                sleep(3);
                $notify = $pay->verify(array_merge($query, ['model'=>$model]));
            }

            if($notify) {
                $message = [];
                $message['out_trade_no'] = $model->out_trade_no;

                // 日志记录
                $logPath = $this->getLogPath(PayEnum::$payTypeAction[$model->pay_type]);
                FileHelper::writeLog($logPath, Json::encode(ArrayHelper::toArray($message)));

                //操作成功，则返回 true .
                if ($this->pay($message)) {
                    $result['verification_status'] = 'true';
                }
                else {
                    throw new \Exception('数据库操作异常');
                }
            }
        } catch (\Exception $e) {
            // 记录报错日志
            $logPath = $this->getLogPath('error');
            FileHelper::writeLog($logPath, $e->getMessage());
        }
        return $result;
    }

    /**
     * 此方法复制于 NotifyController.php
     * @param $message
     * @return bool
     */
    protected function pay($message)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!($payLog = Yii::$app->services->pay->findByOutTradeNo($message['out_trade_no']))) {
                throw new UnprocessableEntityHttpException('找不到支付信息');
            };

            // 支付完成
            if ($payLog->pay_status == StatusEnum::ENABLED) {
                return true;
            };

            $payLog->attributes = $message;
            $payLog->pay_status = StatusEnum::ENABLED;
            $payLog->pay_time = time();
            if (!$payLog->save()) {
                throw new UnprocessableEntityHttpException('日志修改失败');
            }

            // 业务回调
            Yii::$app->services->pay->notify($payLog, null);

            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();

            // 记录报错日志
            $logPath = $this->getLogPath('error');
            FileHelper::writeLog($logPath, $e->getMessage());
            return false;
        }
    }

    /**
     * @param $type
     * @return string
     */
    protected function getLogPath($type)
    {
        return Yii::getAlias('@runtime') . "/pay-logs/" . date('Y_m_d') . '/' . $type . '.txt';
    }
}