<?php

namespace backend\modules\market\controllers;

use backend\controllers\BaseController;
use common\models\base\SearchModel;
use common\models\market\MarketCard;
use common\models\order\Order;
use yii\web\Controller;

/**
 * Default controller for the `market` module
 */
class CardController extends BaseController
{
    /**
     * @var MarketCard
     */
    public $modelClass = MarketCard::class;

    /**
     * Renders the index view for the module
     * @return string
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
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionView()
    {
        return $this->render($this->action->id);
    }
}
