<?php

namespace backend\modules\member\controllers;

use common\models\member\Member;
use Yii;
use common\models\member\Book;
use common\components\Curd;
use common\models\base\SearchModel;
use backend\controllers\BaseController;
use common\helpers\ResultHelper;
use common\helpers\ExcelHelper;

/**
* Book
*
* Class BookController
* @package backend\modules\member\controllers
*/
class BookController extends BaseController
{
    use Curd;
    public $enableCsrfValidation = false;
    /**
    * @var Book
    */
    public $modelClass = Book::class;


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
            'partialMatchAttributes' => [], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query->joinWith(['member']);
        if($title){
            $dataProvider->query->andFilterWhere(['or',['like','title',$title],['like','content',$title],['=','email',$title]]);
        }
        if($start_time && $end_time){
            $dataProvider->query->andFilterWhere(['between',$this->modelClass::tableName().'.created_at', strtotime($start_time), strtotime($end_time)]);
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }


    /**
     * 详情
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionDetail()
    {
        $member_id = Yii::$app->request->get('member_id',null);
        return $this->render('detail', [
            'member_id' => $member_id
        ]);
    }

    //获取
    public  function actionAjaxDetail()
    {
        $member_id = Yii::$app->request->post('member_id',null);
        $title = Yii::$app->request->post('title',null);
        $start_time = Yii::$app->request->post('start_time', null);
        $end_time = Yii::$app->request->post('end_time', null);
        $page = intval(Yii::$app->request->post('pageIndex',1));
        $searchModel = Book::find()->alias('b')
            ->leftJoin(Member::tableName()." m", 'm.id = b.member_id')
            ->andWhere(['=','b.member_id',$member_id]);
        if($title){
            $searchModel->andFilterWhere(['or',['like','b.title',$title],['like','b.content',$title]]);
        }
        if($start_time && $end_time){
            $searchModel->andFilterWhere(['between','b.created_at', strtotime($start_time), strtotime($end_time)]);
        }

        $pageSize = 3; //每页显示数
        $total = $searchModel->count();
        $totalPage = ceil($total / $pageSize); //总页数
        $startPage = $page * $pageSize; //开始页数

        $book_list = $searchModel
            ->limit($pageSize)
            ->offset($startPage)
            ->orderBy('b.created_at desc')
            ->select(['b.title','b.created_at','b.content','b.status','b.remark','m.email'])
            ->asArray()
            ->all();
        foreach ($book_list as $key=>$value){
            $book_list[$key]['created_at'] = Yii::$app->formatter->asDatetime($value['created_at']);
        }
        $data['totalPage'] = $totalPage;
        $data['list'] = $book_list;
        return ResultHelper::json(200, 'success',$data);

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
            ['用户账号', 'email', 'text'],
            ['留言主题', 'title', 'text'],
            ['留言内容', 'content', 'text'],
            ['跟进状态', 'status', 'selectd', [0 => '未回复', 1 => '已回复',2 => '无效']],
            ['备注', 'remark', 'text'],
            ['留言时间', 'created_at', 'date', 'Y-m-d'],
        ];
        $searchModel = Book::find()->alias('b')
            ->leftJoin(Member::tableName()." m", 'm.id = b.member_id');

        if($title){
            $searchModel->andFilterWhere(['or',['like','b.title',$title],['like','b.content',$title]]);
        }
        if($start_time && $end_time){
            $searchModel->andFilterWhere(['between','b.created_at', strtotime($start_time), strtotime($end_time)]);
        }

        $list = $searchModel
            ->orderBy('b.created_at desc')
            ->select(['b.*','m.email'])
            ->asArray()
            ->all();

        return ExcelHelper::exportData($list, $header, 'Curd数据导出_' . time());
    }



}
