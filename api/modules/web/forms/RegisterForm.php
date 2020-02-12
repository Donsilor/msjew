<?php

namespace api\modules\web\forms;

use yii\base\Model;
use common\helpers\RegularHelper;
use common\models\member\Member;
use common\models\api\AccessToken;
use common\models\common\SmsLog;
use common\models\validators\SmsCodeValidator;

/**
 * Class RegisterForm
 * @package api\modules\v1\forms
 * @author jianyan74 <751393839@qq.com>
 */
class RegisterForm extends Model
{
    public $mobile;
    public $password;
    public $password_repetition;
    public $code;
    public $group;
    public $realname;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mobile', 'group', 'code', 'password', 'password_repetition', 'realname'], 'required'],
            [['realname'], 'string'],
            [['password'], 'string', 'min' => 6],
            [
                ['mobile'],
                'unique',
                'targetClass' => Member::class,
                'targetAttribute' => 'mobile',
                'message' => '手机号已存在。'
            ],
            ['code', SmsCodeValidator::class, 'usage' => SmsLog::USAGE_REGISTER],
            ['mobile', 'match', 'pattern' => RegularHelper::mobile(), 'message' => '请输入正确的手机号'],
            [['password_repetition'], 'compare', 'compareAttribute' => 'password','message' => '重复密码错误'],// 验证新密码和重复密码是否相等
            ['group', 'in', 'range' => AccessToken::$ruleGroupRnage],
        ];
    }

    public function attributeLabels()
    {
        return [
            'mobile' => '手机号码',
            'realname' => '姓名',
            'password' => '密码',
            'password_repetition' => '重复密码',
            'group' => '类型',
            'code' => '验证码',
        ];
    }
}