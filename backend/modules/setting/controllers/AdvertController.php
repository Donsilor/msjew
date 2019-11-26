<?php

namespace backend\modules\setting\controllers;

use Yii;
use common\models\setting\Advert;
use common\components\Curd;
use common\models\base\SearchModel;
use backend\controllers\BaseController;

/**
* Advert
*
* Class AdvertController
* @package backend\modules\setting\controllers
*/
class AdvertController extends BaseController
{
    use Curd;

    /**
    * @var Advert
    */
    public $modelClass = Advert::class;


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

        $dataProvider->query->with(['lang'=>function($query){
            $query->where(['language'=>Yii::$app->language]);
        }]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
}
