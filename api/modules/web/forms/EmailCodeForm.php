<?php

namespace api\modules\web\forms;

use Yii;
use yii\base\Model;
use common\helpers\RegularHelper;
use common\models\common\EmailLog;

/**
 * Class SmsCodeForm
 * @package api\modules\v1\forms
 * @author jianyan74 <751393839@qq.com>
 */
class EmailCodeForm extends Model
{
    /**
     * @var
     */
    public $email;
    
    /**
     * @var
     */
    public $usage;
    
    /**
     * @return array
     */
    public function rules()
    {
        return [
                [['email', 'usage'], 'required'],
                [['usage'], 'in', 'range' => array_keys(EmailLog::$usageExplain)],
                ['email', 'match', 'pattern' => RegularHelper::email(), 'message' => '请输入正确的邮箱地址'],
        ];
    }
    
    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
                'email' => '邮箱',
                'usage' => '用途',
        ];
    }
    
    /**
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function send()
    {
        $code = rand(10000, 99999);
        return Yii::$app->services->mailer->send($this->email,$this->usage,['code'=>$code]);
    }
}