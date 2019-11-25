<?php

namespace backend\modules\setting\controllers;

use Yii;
use common\models\setting\AdvertImages;
use common\components\Curd;
use common\models\base\SearchModel;
use backend\controllers\BaseController;

/**
* AdvertImages
*
* Class AdvertImagesController
* @package backend\modules\setting\controllers
*/
class AdvertImagesController extends BaseController
{
    use Curd;

    /**
    * @var AdvertImages
    */
    public $modelClass = AdvertImages::class;


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
}
