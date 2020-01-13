<?php

namespace api\modules\web\controllers;

use Yii;
use yii\base\Exception;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;
use api\controllers\OnAuthController;
use common\helpers\ResultHelper;
use common\helpers\ArrayHelper;
use common\models\member\Member;
use api\modules\web\forms\LoginForm;
use api\modules\web\forms\RefreshForm;
use api\modules\web\forms\MobileLogin;
use api\modules\web\forms\SmsCodeForm;
use api\modules\web\forms\EmailCodeForm;
use api\modules\web\forms\MobileRegisterForm;
use api\modules\web\forms\EmailRegisterForm;
use api\modules\web\forms\EmailUpPwdForm;
use api\modules\web\forms\MobileUpPwdForm;
use Zhuzhichao\IpLocationZh\Ip;


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
    protected $authOptional = ['ip','login', 'refresh', 'mobile-login', 'sms-code','email-code', 'mobile-register','email-register', 'mobile-up-pwd','email-up-pwd'];
    
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
    public function actionIp(){
        $last_ip  = \Yii::$app->request->userIP;
        $location = \Yii::$app->ipLocation->getLocation($last_ip);
        echo '<pre/>';
        echo $last_ip,'--';
        echo 'myweishanli/yii2-ip2location:<br/>';
        print_r($location);  
        echo "<br/>";
        echo 'zhuzhichao/ip-location-zh:<br/>',$last_ip,'--';
        $location = Ip::find($last_ip);
        print_r($location);
        exit;
    }
    /**
     * 手机注册
     *
     * @return array|mixed
     * @throws \yii\base\Exception
     */
    public function actionMobileRegister()
    {  
        
        try {            
            $trans = \Yii::$app->db->beginTransaction();
            $model = new MobileRegisterForm();
            $model->attributes = Yii::$app->request->post();
            if (!$model->validate()) {
                throw new UnprocessableEntityHttpException($this->getError($model));
            }
    
            $member = new Member();
            $member->attributes = ArrayHelper::toArray($model);
            $member->password_hash = Yii::$app->security->generatePasswordHash($model->password);
            $member->username = $model->mobile;
            $member->realname = $model->lastname.''.$model->firstname;
            $this->buildFirstIpLocation($member);
            
            if (!$member->save()) {
                throw new UnprocessableEntityHttpException($this->getError($member));
            }
    
            $trans->commit();
            return Yii::$app->services->apiAccessToken->getAccessToken($member, $model->group);
            
        } catch (Exception $e){
            $trans->rollBack();
            throw $e;
        }
    }    
    
    /**
     * 手机重置密码
     *
     * @return array|mixed
     * @throws \yii\base\Exception
     */
    public function actionMobileUpPwd()
    {        
        try {               
            $trans = \Yii::$app->db->beginTransaction();
            $model = new MobileUpPwdForm();
            $model->attributes = Yii::$app->request->post();
            if (!$model->validate()) {
                throw new UnprocessableEntityHttpException($this->getError($model));
            }            
            $member = $model->getUser();
            $member->password_hash = Yii::$app->security->generatePasswordHash($model->password);
            if (!$member->save()) {
                throw new UnprocessableEntityHttpException($this->getError($member));
            }
            $trans->commit();
            return Yii::$app->services->apiAccessToken->getAccessToken($member, $model->group);            
        } catch (Exception $e){
            $trans->rollBack();
            throw $e;
        }
    }
    
    /**
     * 邮箱注册
     *
     * @return array|mixed
     * @throws \yii\base\Exception
     */
    public function actionEmailRegister()
    {        
        try {            
            $trans = \Yii::$app->db->beginTransaction();
            $model = new EmailRegisterForm();
            $model->attributes = Yii::$app->request->post();
            if (!$model->validate()) {
                throw new UnprocessableEntityHttpException($this->getError($model));
            }
    
            $member = new Member();
            $member->attributes = ArrayHelper::toArray($model);
            $member->password_hash = Yii::$app->security->generatePasswordHash($model->password);
            $member->username = $model->email;
            $this->buildFirstIpLocation($member);
            if (!$member->save()) {
                throw new UnprocessableEntityHttpException($this->getError($member));
            }  
            $trans->commit();
            return Yii::$app->services->apiAccessToken->getAccessToken($member, $model->group);            
        } catch (Exception $e){
            $trans->rollBack();
            throw $e;
        }
    }

    /**
     * 邮箱重置密码
     *
     * @return array|mixed
     * @throws \yii\base\Exception
     */
    public function actionEmailUpPwd()
    {        
        try {            
            $trans = \Yii::$app->db->beginTransaction();
            $model = new EmailUpPwdForm();
            $model->attributes = Yii::$app->request->post();
            if (!$model->validate()) {
                throw new UnprocessableEntityHttpException($this->getError($model));
            }
    
            $member = $model->getUser();
            $member->password_hash = Yii::$app->security->generatePasswordHash($model->password);
            if (!$member->save()) {
                throw new UnprocessableEntityHttpException($this->getError($member));
            }
            
            $trans->commit();
            return Yii::$app->services->apiAccessToken->getAccessToken($member, $model->group);
        } catch (Exception $e){
            $trans->rollBack();
            throw $e;
        }
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
    
    /**
     * 用户首次注册ip
     * @param Member $member
     */
    private function buildFirstIpLocation(& $member)
    {
        $member->last_ip  = \Yii::$app->request->getRemoteIP();
        $member->first_ip  = $member->last_ip;
        list(,$member->first_ip_location) = \Yii::$app->ipLocation->getLocation($member->first_ip);
    }
     /**
     * 用户首次注册ip
     * @param Member $member
     */
    private function buildLastIpLocation(& $member)
    {
        $member->last_ip  = \Yii::$app->request->getRemoteIP();
        $member->last_time = time();
    }
}
