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
            ->search(Yii::$app->request->queryParams);

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
