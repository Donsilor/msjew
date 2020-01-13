<?php

namespace api\modules\wap\controllers\member;

use Yii;
use yii\web\NotFoundHttpException;
use api\controllers\OnAuthController;
use common\enums\StatusEnum;
use common\models\member\Member;
use common\helpers\ResultHelper;
use api\modules\web\forms\UpPwdForm;

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
    protected $authOptional = [];

    /**
     * 用户登录基本信息
     * @throws NotFoundHttpException
     * @return \yii\db\ActiveRecord|array|NULL
     */
    public function actionMe()
    {
        $fields = [
                'member.id', 'username','firstname','lastname','realname', 'nickname','google_account',
                'facebook_account','head_portrait', 'gender','marriage','qq', 'email', 'birthday','status','created_at'
        ];
        $model = Member::find()->select($fields)
                        ->joinWith("account")
                        ->where(['member.id' => $this->member_id, 'status' => StatusEnum::ENABLED])
                        ->asArray()->one();

        if (!$model) {
            return ResultHelper::api(401, "请求的数据不存在或您的权限不足");
        }

        return $model;
    }
    /**
     * 编辑用户基本信息
     * @return mixed|NULL|array
     */
    public function actionEdit()
    {    
        $member = Member::find()->where(['id' => $this->member_id])->one();
        if (!$member) {
            return ResultHelper::api(401, "请求的数据不存在或您的权限不足");
        }
        $member->attributes = \Yii::$app->request->post();
        
        $allows = ['firstname','lastname','gender','birthday','marriage','google_account','facebook_account'];
        if (false === $member->save(true,$allows)) {
            return ResultHelper::api(422, $this->getError($member));
        }
        
        return $member->toArray(array_merge(['id'],$allows));        
    }
    /**
     * 修改密码
     *
     * @return array|bool
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     */
    public function actionUpPwd()
    {
        $model = new UpPwdForm();
        $model->attributes = Yii::$app->request->post();
        $model->member_id = $this->member_id;
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
        if (in_array($action, ['delete', 'index'])) {
            throw new \yii\web\BadRequestHttpException('权限不足');
        }
    }

}
