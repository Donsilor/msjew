<?php

namespace api\modules\v1\controllers\member;

use api\modules\v1\forms\LoginForm;
use common\helpers\ResultHelper;
use Yii;
use yii\web\NotFoundHttpException;
use api\controllers\OnAuthController;
use common\enums\StatusEnum;
use common\models\member\Member;

/**
 * 会员接口
 *
 * Class MemberController
 * @package api\modules\v1\controllers\member
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class MemberController extends OnAuthController
{
    /**
     * @var Member
     */
    public $modelClass = Member::class;
    protected $authOptional = ['login'];

    /**
     * 单个显示
     *
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->modelClass::find()
            ->where(['id' => $id, 'status' => StatusEnum::ENABLED])
            ->select([
                'id', 'username', 'nickname',
                'realname', 'head_portrait', 'gender',
                'qq', 'email', 'birthday',
                'user_money', 'user_integral', 'status',
                'created_at'
            ])
            ->asArray()
            ->one();

        if (!$model) {
            throw new NotFoundHttpException('请求的数据不存在或您的权限不足.');
        }

        return $model;
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
        if (in_array($action, ['delete', 'index'])) {
            throw new \yii\web\BadRequestHttpException('权限不足');
        }
    }

    //登陆
    public function actionLogin()
    {
        $username = Yii::$app->request->post('username',null);
        if($username == null) return ResultHelper::api(400, '参数错误');
        $model = new LoginForm();
        $model->username = $username;
        $user = $model->login();
        return ['user_id'=>$user->id];



    }



}
