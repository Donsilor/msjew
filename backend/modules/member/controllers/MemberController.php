<?php

namespace backend\modules\member\controllers;

use common\models\member\Book;
use Yii;
use common\models\base\SearchModel;
use common\components\Curd;
use common\models\member\Member;
use common\enums\StatusEnum;
use backend\controllers\BaseController;
use backend\modules\member\forms\RechargeForm;
use common\helpers\ExcelHelper;

/**
 * 会员管理
 *
 * Class MemberController
 * @package backend\modules\member\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class MemberController extends BaseController
{
    use Curd;

    /**
     * @var \yii\db\ActiveRecord
     */
    public $modelClass = Member::class;

    /**
     * 首页
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $title = Yii::$app->request->post('title',null);
        $start_time = Yii::$app->request->post('start_time', null);
        $end_time = Yii::$app->request->post('end_time', null);
        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'partialMatchAttributes' => ['realname', 'mobile'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams,['created_at','visit_count','is_book']);

        if($title){
            $dataProvider->query->andFilterWhere(['=','email',$title]);
        }
        if($start_time && $end_time){
            $dataProvider->query->andFilterWhere(['between','created_at', strtotime($start_time), strtotime($end_time)]);
        }

        $created_at = $searchModel->created_at;
        if (!empty($created_at)) {
            $dataProvider->query->andFilterWhere(['>=','created_at', strtotime(explode('/', $searchModel->created_at)[0])]);//起始时间
            $dataProvider->query->andFilterWhere(['<','created_at', (strtotime(explode('/', $searchModel->created_at)[1]) + 86400)] );//结束时间
        }

        //是否首次登陆
        $visit_count = $searchModel->visit_count;
        if (!empty($visit_count)) {
            if($visit_count  == 1){
                $dataProvider->query->andFilterWhere(['=','visit_count',1 ]);
            }else{
                $dataProvider->query->andFilterWhere(['>','visit_count',1 ]);
            }
        }

        //是否留言
        $is_book = $searchModel->is_book;
        if (!empty($is_book)) {
            $member_ids = Book::find()->select(['member_id'])->distinct()->asArray()->all();
            $member_ids = array_column($member_ids,'member_id');
            if($is_book  == 1){
                $dataProvider->query->andFilterWhere(['in','id',$member_ids ]);
            }else{
                $dataProvider->query->andFilterWhere(['not in','id',$member_ids ]);
            }

        }

        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->with('account');

        return $this->render('member', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,

        ]);
    }

    /**
     * 编辑/创建
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\Exception
     * @throws \yii\base\ExitException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionAjaxEdit()
    {
        $id = Yii::$app->request->get('id');
        $model = $this->findModel($id);
        $model->scenario = 'backendCreate';
        $modelInfo = clone $model;

        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            // 验证密码
            if ($modelInfo['password_hash'] != $model->password_hash) {
                $model->password_hash = Yii::$app->security->generatePasswordHash($model->password_hash);
            }

            return $model->save()
                ? $this->redirect(['index'])
                : $this->message($this->getError($model), $this->redirect(['index']), 'error');
        }

        return $this->renderAjax($this->action->id, [
            'model' => $model,
        ]);
    }

    /**
     * 积分/余额变更
     *
     * @param $id
     * @return mixed|string
     * @throws \yii\base\ExitException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function actionRecharge($id)
    {
        $rechargeForm = new RechargeForm();
        $member = $this->findModel($id);

        // ajax 校验
        $this->activeFormValidate($rechargeForm);
        if ($rechargeForm->load(Yii::$app->request->post())) {
            if (!$rechargeForm->save($member)) {
                return $this->message($this->getError($rechargeForm), $this->redirect(['index']), 'error');
            }

            return $this->message('充值成功', $this->redirect(['index']));
        }

        return $this->renderAjax($this->action->id, [
            'model' => $member,
            'rechargeForm' => $rechargeForm,
        ]);
    }

    /**
     * 导出Excel
     *
     * @return bool
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function actionExport()
    {
        $title = Yii::$app->request->get('title',null);
        $start_time = Yii::$app->request->get('start_time', null);
        $end_time = Yii::$app->request->get('end_time', null);

        // [名称, 字段名, 类型, 类型规则]
        $header = [
            ['ID', 'id'],
            ['账号', 'email', 'text'],
            ['注册时间', 'created_at', 'date', 'Y-m-d'],
            ['首次登陆', 'visit_count', 'function', function($m){ return $m == 1 ? "是":"否";}],
            ['是否留言', 'id', 'function' ,function($m){
                $count = \common\models\member\Book::find()->where(['member_id'=>$m])->count();
                return $count > 0 ? "是":"否";
            }]

        ];
        $searchModel = Member::find();

        if($title){
            $searchModel->andFilterWhere(['=','email',$title]);
        }
        if($start_time && $end_time){
            $searchModel->andFilterWhere(['between','created_at', strtotime($start_time), strtotime($end_time)]);
        }

        $list = $searchModel
            ->orderBy('created_at desc')
            ->asArray()
            ->all();


        return ExcelHelper::exportData($list, $header, 'Curd数据导出_' . time());
    }
}