<?php

namespace backend\modules\member\controllers;

use Yii;
use common\models\member\Contact;
use common\components\Curd;
use common\models\base\SearchModel;
use backend\controllers\BaseController;

/**
* Contact
*
* Class ContactController
* @package backend\modules\member\controllers
*/
class ContactController extends BaseController
{
    use Curd;

    /**
    * @var Contact
    */
    public $modelClass = Contact::class;


    /**
    * 首页
    *
    * @return string
    * @throws \yii\web\NotFoundHttpException
    */
    public function actionIndex()
    {
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
            ->search(Yii::$app->request->queryParams,['created_at','book_time']);

        $created_at = $searchModel->created_at;
        if (!empty($created_at)) {
            $dataProvider->query->andFilterWhere(['>=','created_at', strtotime(explode('/', $searchModel->created_at)[0])]);//起始时间
            $dataProvider->query->andFilterWhere(['<','created_at', (strtotime(explode('/', $searchModel->created_at)[1]) + 86400)] );//结束时间
        }

        $book_time = $searchModel->book_time;
        if (!empty($book_time)) {
            $dataProvider->query->andFilterWhere(['>=','book_time', explode('/', $searchModel->book_time)[0]]);//起始时间
            $dataProvider->query->andFilterWhere(['<','book_time', date('Y-m-d',strtotime("+1 day",strtotime(explode('/', $searchModel->book_time)[1])))] );//结束时间
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionInfo()
    {
        $id = Yii::$app->request->get('id');
        $model = $this->findModel($id);
        return $this->render('info', [
            'model' => $model,
        ]);
    }
}
