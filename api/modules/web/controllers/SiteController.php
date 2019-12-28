<?php

namespace api\modules\web\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use api\controllers\OnAuthController;
use common\helpers\ResultHelper;
use common\helpers\ArrayHelper;
use common\models\member\Member;
use api\modules\web\forms\UpPwdForm;
use api\modules\web\forms\LoginForm;
use api\modules\web\forms\RefreshForm;
use api\modules\web\forms\MobileLogin;
use api\modules\web\forms\SmsCodeForm;
use api\modules\web\forms\EmailCodeForm;
use api\modules\web\forms\MobileRegisterForm;
use api\modules\web\forms\EmailRegisterForm;
use api\modules\web\forms\EmailUpPwdForm;

/**
 * 登录接口
 *
 * Class SiteController
 * @package api\modules\v1\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class SiteController extends OnAuthController
{
    public $modelClass = '';

    /**
     * 不用进行登录验证的方法
     *
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $authOptional = ['login', 'refresh', 'mobile-login', 'sms-code','email-code', 'mobile-register','email-register', 'up-pwd'];
    
    /**
     * 登录根据用户信息返回accessToken
     *
     * @return array|bool
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     */
    public function actionLogin()
    {
        $model = new LoginForm();
        $model->attributes = Yii::$app->request->post();
        if ($model->validate()) {
            return Yii::$app->services->apiAccessToken->getAccessToken($model->getUser(), $model->group);
        }

        // 返回数据验证失败
        return ResultHelper::api(422, $this->getError($model));
    }

    /**
     * 登出
     *
     * @return array|mixed
     */
    public function actionLogout()
    {
        if (Yii::$app->services->apiAccessToken->disableByAccessToken(Yii::$app->user->identity->access_token)) {
            return ResultHelper::api(200, '退出成功');
        }

        return ResultHelper::api(200, '退出失败');
    }

    /**
     * 重置令牌
     *
     * @param $refresh_token
     * @return array
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     */
    public function actionRefresh()
    {
        $model = new RefreshForm();
        $model->attributes = Yii::$app->request->post();
        if (!$model->validate()) {
            return ResultHelper::api(422, $this->getError($model));
        }

        return Yii::$app->services->apiAccessToken->getAccessToken($model->getUser(), $model->group);
    }

    /**
     * 手机验证码登录
     *
     * @return array|mixed
     * @throws \yii\base\Exception
     */
    public function actionMobileLogin()
    {
        $model = new MobileLogin();
        $model->attributes = Yii::$app->request->post();
        if ($model->validate()) {
            return Yii::$app->services->apiAccessToken->getAccessToken($model->getUser(), $model->group);
        }

        // 返回数据验证失败
        return ResultHelper::api(422, $this->getError($model));
    }

    /**
     * 获取验证码
     *
     * @return int|mixed
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function actionSmsCode()
    {
        $model = new SmsCodeForm();
        $model->attributes = Yii::$app->request->post();
        if (!$model->validate()) {
            return ResultHelper::api(422, $this->getError($model));
        }

        return $model->send();
    }
    
    /**
     * 获取邮箱验证码
     *
     * @return int|mixed
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function actionEmailCode()
    {
        $model = new EmailCodeForm();
        $model->attributes = Yii::$app->request->post();
        if (!$model->validate()) {
            return ResultHelper::api(422, $this->getError($model));
        }
        
        return $model->send();
    }

    /**
     * 手机注册
     *
     * @return array|mixed
     * @throws \yii\base\Exception
     */
    public function actionMobileRegister()
    {
        $model = new MobileRegisterForm();
        $model->attributes = Yii::$app->request->post();
        if (!$model->validate()) {
            return ResultHelper::api(422, $this->getError($model));
        }

        $member = new Member();
        $member->attributes = ArrayHelper::toArray($model);
        $member->password_hash = Yii::$app->security->generatePasswordHash($model->password);
        $member->username = $model->mobile;
        $member->realname = $model->lastname.''.$model->firstname;

        if (!$member->save()) {
            return ResultHelper::api(422, $this->getError($member));
        }

        return Yii::$app->services->apiAccessToken->getAccessToken($member, $model->group);
    }
    
    /**
     * 邮箱注册
     *
     * @return array|mixed
     * @throws \yii\base\Exception
     */
    public function actionEmailRegister()
    {
        $model = new EmailRegisterForm();
        $model->attributes = Yii::$app->request->post();
        if (!$model->validate()) {
            return ResultHelper::api(422, $this->getError($model));
        }

        $member = new Member();
        $member->attributes = ArrayHelper::toArray($model);
        $member->password_hash = Yii::$app->security->generatePasswordHash($model->password);
        $member->username = $model->email;
        if (!$member->save()) {
            return ResultHelper::api(422, $this->getError($member));
        }
        
        return Yii::$app->services->apiAccessToken->getAccessToken($member, $model->group);
    }

    /**
     * 密码重置
     *
     * @return array|mixed
     * @throws \yii\base\Exception
     */
    public function actionEmailUpPwd()
    {
        $model = new EmailUpPwdForm();
        $model->attributes = Yii::$app->request->post();
        if (!$model->validate()) {
            return ResultHelper::api(422, $this->getError($model));
        }

        $member = $model->getUser();
        $member->password_hash = Yii::$app->security->generatePasswordHash($model->password);
        if (!$member->save()) {
            return ResultHelper::api(422, $this->getError($member));
        }
        
        return Yii::$app->services->apiAccessToken->getAccessToken($member, $model->group);
    }

    /**
     * 权限验证
     *
     * @param string $action 当前的方法
     * @param null $model 当前的模型类
     * @param array $params $_GET变量
     * @throws \yii\web\BadRequestHttpException
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        // 方法名称
        if (in_array($action, ['index', 'view', 'update', 'create', 'delete'])) {
            throw new \yii\web\BadRequestHttpException('权限不足');
        }
    }
}
