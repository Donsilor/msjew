<?php

namespace common\queues;

use Yii;
use yii\base\Exception;

/**
 * Class SmsJob
 * @package common\queues
 * @author jianyan74 <751393839@qq.com>
 */
class SmsJob extends Job
{
    /**
     * @var
     */
    public $mobile;

    /**
     * @var
     */
    public $usage;

    /**
     * @var
     */
    public $data;

    /**
     * @param \yii\queue\Queue $queue
     * @return mixed|void
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function execute($queue)
    {        
        try {
            
            echo date("Y-m-d H:i:s").'=>send mobile start =>'.$this->mobile.PHP_EOL;
            $res = Yii::$app->services->sms->realSend($this->mobile, $this->usage, $this->data);
            if($res) {
                echo date("Y-m-d H:i:s").'=>send mobile success!'.var_export($res,true).PHP_EOL;
            }else{
                echo date("Y-m-d H:i:s").'=>send mobile failed!'.var_export($res,true).PHP_EOL;
            }
        }catch (Exception $e) {
            echo date("Y-m-d H:i:s").'=>send mobile faild!'.$e->getMessage().PHP_EOL;
            throw  $e ;
        }     
    }
    
    public function canRetry($attempt, $error)
    {
        return $attempt < 1;
    }
}