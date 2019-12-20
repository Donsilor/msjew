<?php

namespace api\modules\v1\controllers\member;

use api\modules\v1\forms\LoginForm;
use common\helpers\ResultHelper;
use common\models\member\Book;
use common\models\member\Member;
use Yii;
use api\controllers\OnAuthController;

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
        $username = Yii::$app->request->get('username',null);
        if($username == null) return ResultHelper::api(400, '缺省参数');
        $page = Yii::$app->request->get('page',null);
        $pageSize = Yii::$app->request->get('pageSize',5); //每页显示数
        $searchModel = $this->modelClass::find()->alias('b')
            ->leftJoin(Member::tableName().' m','m.id = b.member_id')
            ->where(['m.email'=>$username])
            ->select(['b.title','from_unixtime(b.created_at,"%Y.%m.%d")  created_at','b.content','m.email'])
            ->orderby('b.created_at desc');
        if($page == null){
            $model = $searchModel->asArray()->all();
             if(empty($model)) return ResultHelper::api(201, '没有数据');
            return ['list'=>$model];
        }
        $startPage = ($page-1) * $pageSize; //开始页数
        $total = $searchModel->count();
        $totalPage = ceil($total / $pageSize); //总页数
        $model = $searchModel
            ->limit($pageSize)
            ->offset($startPage)
            ->asArray()
            ->all();

        if(empty($model)) return ResultHelper::api(201, '没有数据');
        return ['totalPage'=>$totalPage ,'list'=>$model];
    }

    /**
     * 添加留言
     *
     * @param int $pid
     * @return array|yii\data\ActiveDataProvider
     */
    public function actionAdd()
    {
        $username = Yii::$app->request->post('username',null);
        $title = Yii::$app->request->post('title',null);
        $content = Yii::$app->request->post('content',null);

        if(!($username && $title && $content)) return ResultHelper::api(400, '缺省参数');
        $loginFrom = new LoginForm();
        $loginFrom->username = $username;
        $user = $loginFrom->login();
        $model = new $this->modelClass;
        $model->title = $title;
        $model->content = $content;
        $model->member_id = $user->id;
        if (!$model->save()) {
            return ResultHelper::api(422, $this->getError($model));
        }
        return $model;

    }




}