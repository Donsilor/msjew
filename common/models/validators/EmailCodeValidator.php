<?php
namespace common\models\validators;

use common\enums\StatusEnum;
use yii\validators\Validator;
use common\models\common\EmailLog;

/**
 * 短信验证码验证器
 *
 * 在rule使用：
 *
 * ['verifyCode', '\common\models\validators\EmailCodeValidator', 'usage' => 'userRegister'],
 *
 * Class EmailCodeValidator
 * @package common\models\common
 */
class EmailCodeValidator extends Validator
{
    /**
     * 对应email_log表中的usage字段，用来匹配不同用途的验证码
     *
     * @var string sms code type
     */
    public $usage;
    
    /**
     * Model或者form中提交的手机号字段名称
     *
     * @var string
     */
    public $emailAttribute = 'email';
    
    /**
     * 验证码过期时间
     *
     * @var int
     */
    public $expireTime = 60 * 15 * 10;
    
    /**
     * @param \yii\base\Model $model
     * @param string $attribute
     */
    public function validateAttribute($model, $attribute)
    {
        $fieldName = $this->emailAttribute;
        $email = $model->$fieldName;
        
        $emailLog = EmailLog::find()->where([
                'email' => $email,
                'error_code' => 200,
                'used' => StatusEnum::DISABLED,
                'usage' => $this->usage,
        ])->orderBy('id desc')->one();

        $time = time();
        if (is_null($emailLog) ||($emailLog->code != $model->$attribute) || ($emailLog->created_at > $time || $time > ($emailLog->created_at + $this->expireTime))) {
            $this->addError($model, $attribute, '验证码错误');
        } else {
            $emailLog->used = StatusEnum::ENABLED;
            $emailLog->use_time = $time;
            $emailLog->save(false);
        }
    }
}
