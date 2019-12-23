<?php

namespace api\modules\v1\controllers\member;

use api\modules\v1\forms\BookForm;
use api\modules\v1\forms\EmailLoginForm;
use common\helpers\ResultHelper;
use common\models\member\Book;
use common\models\member\Member;
use Yii;
use api\controllers\OnAuthController;
use yii\helpers\ArrayHelper;

/**
 * Class ProvincesController
 * @package api\modules\v1\controllers\member
 * @author jianyan74 <751393839@qq.com>
 */
class BookController extends OnAuthController
{
    /**
     * @var Provinces
     */
    public $modelClass = Book::class;
    protected $authOptional = ['index','create','add'];
    /**
     * 根据邮箱获取留言
     *
     * @param int $pid
     * @return array|yii\data\ActiveDataProvider
     */



    public function actionIndex()
    {
        $username = Yii::$app->request->get('username');
        if(!$username){
            return ResultHelper::api(400, '用户名不能为空');
        }
        $page = Yii::$app->request->get('page',1);
        $page_size = Yii::$app->request->get('page_size',5); //每页显示数
        $query = $this->modelClass::find()->alias('b')
            ->leftJoin(Member::tableName().' m','m.id = b.member_id')
            ->where(['m.email'=>$username])
            ->select(['b.title','from_unixtime(b.created_at,"%Y.%m.%d")  created_at','b.content','m.email'])
            ->orderby('b.created_at desc');

        $result = $this->pagination($query,$page,$page_size);
        return $result;
    }

    /**
     * 添加留言
     *
     * @param int $pid
     * @return array|yii\data\ActiveDataProvider
     */
    public function actionCreate()
    {
        //登陆
        $loginFrom = new EmailLoginForm();
        $loginFrom->attributes = Yii::$app->request->post();
        if (!$loginFrom->validate()) {
            return ResultHelper::api(422, $this->getError($loginFrom));
        }
        $user = $loginFrom->login();

        //验证
        $model = new BookForm();
        $model->member_id = $user->id;
        $model->attributes = Yii::$app->request->post();
        if (!$model->validate()) {
            return ResultHelper::api(422, $this->getError($model));
        }
        //提交
        $book = new $this->modelClass();
        $book->attributes = ArrayHelper::toArray($model);
        if (!$book->save()) {
            return ResultHelper::api(422, $this->getError($book));
        }
        return $book;

    }




}