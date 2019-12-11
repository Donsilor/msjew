<?php

namespace backend\modules\goods\controllers;

use Yii;
use common\models\goods\Goods;
use common\components\Curd;
use common\models\base\SearchModel;
use backend\controllers\BaseController;

/**
* Goods
*
* Class GoodsController
* @package backend\modules\goods\controllers
*/
class GoodsController extends BaseController
{
    use Curd;

    /**
    * @var Goods
    */
    public $modelClass = Goods::class;


    /**
    * 首页
    *
    * @return string
    * @throws \yii\web\NotFoundHttpException
    */
    public function actionIndex()
    {
        $type_id = Yii::$app->request->get('type_id',0);
        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'partialMatchAttributes' => [], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $typeModel = Yii::$app->services->goodsType->getAllTypesById($type_id);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'typeModel'  =>$typeModel,
        ]);
    }
}
