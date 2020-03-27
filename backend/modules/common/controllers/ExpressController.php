<?php

namespace backend\modules\common\controllers;

use Yii;
use common\models\common\Express;
use common\components\Curd;
use common\models\base\SearchModel;
use backend\controllers\BaseController;
use yii\base\Exception;

/**
* WebSeo
*
* Class WebSeoController
* @package backend\modules\setting\controllers
*/
class ExpressController extends BaseController
{
    use Curd;

    /**
    * @var WebSeo
    */
    public $modelClass = Express::class;


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
            'pageSize' => $this->pageSize,
//            'relations' => [
//                'lang' => ['express_name'],
//                ]
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams,['express_name']);
        $dataProvider->query->joinWith(['lang']);
        $dataProvider->query->andFilterWhere(['like', 'lang.express_name',$searchModel->express_name]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }


}
