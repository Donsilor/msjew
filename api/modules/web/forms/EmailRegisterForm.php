<?php

namespace api\modules\web\forms;

use yii\base\Model;
use common\helpers\RegularHelper;
use common\models\member\Member;
use common\models\validators\EmailCodeValidator;
use common\models\common\EmailLog;
use common\models\api\AccessToken;

/**
 * Class RegisterForm
 * @package api\modules\v1\forms
 * @author jianyan74 <751393839@qq.com>
 */
class EmailRegisterForm extends Model
{
    public $email;
    public $password;
    public $password_repetition;
    public $code;
    public $group = 'front';
    public $realname;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
                [['email', 'code', 'password', 'password_repetition', 'realname'], 'required'],
                [['realname'], 'string'],
                [['password'], 'string', 'min' => 6],
                [
                        ['email'],
                        'unique',
                        'targetClass' => Member::class,
                        'targetAttribute' => 'mobile',
                        'message' => '邮箱已存在。'
                ],                
                ['email', 'match', 'pattern' => RegularHelper::email(), 'message' => '请输入正确的邮箱'],
                ['code', EmailCodeValidator::class, 'usage' => EmailLog::USAGE_REGISTER],
                [['password_repetition'], 'compare', 'compareAttribute' => 'password','message' => '重复密码错误'],// 验证新密码和重复密码是否相等
                ['group', 'in', 'range' => AccessToken::$ruleGroupRnage],
        ];
    }
    
    public function attributeLabels()
    {
        return [
                'email' => '邮箱',
                'realname' => '姓名',
                'password' => '密码',
                'password_repetition' => '重复密码',
                'group' => '类型',
                'code' => '验证码',
        ];
    }
}