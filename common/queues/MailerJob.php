<?php

namespace common\queues;

use Yii;
use yii\base\BaseObject;
use yii\base\Exception;

/**
 * 发送邮件
 *
 * Class MailerJob
 * @package common\queues
 * @author jianyan74 <751393839@qq.com>
 */
class MailerJob extends BaseObject implements \yii\queue\JobInterface
{

    /**
     * 邮箱
     *
     * @var
     */
    public $email;

    /**
     * 主题(标题)
     *
     * @var
     */
    public $subject;

    /**
     * 邮件模板
     *
     * @var
     */
    public $template;
    /**
     * 用途
     * @var unknown
     */
    public $usage;
    /**
     * 模板参数
     * @var array
     */
    public $data;

    /**
     * @param \yii\queue\Queue $queue
     * @return mixed|void
     * @throws \yii\base\InvalidConfigException
     */
    public function execute($queue)
    {
        try{
            $res = Yii::$app->services->mailer->realSend($this->email, $this->subject, $this->template, $this->usage, $this->data);
            if($res) {
                echo date("Y-m-d H:i:s").'=>send email success!'.var_export($res,true).PHP_EOL;
            }else{
                echo date("Y-m-d H:i:s").'=>send email failed!'.var_export($res,true).PHP_EOL;
            }
        }catch (Exception $e) {
            echo date("Y-m-d H:i:s").'=>send email faild!'.$e->getMessage().PHP_EOL;
            throw  $e ;
        }        
    }
}