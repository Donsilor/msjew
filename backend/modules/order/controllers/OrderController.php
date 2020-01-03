<?php

namespace backend\modules\order\controllers;

use backend\controllers\BaseController;
use common\enums\OrderStatusEnum;
use Yii;
use common\components\Curd;
use common\enums\AppEnum;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use common\models\order\Order;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `order` module
 */
class OrderController extends BaseController
{
    use Curd;

    /**
     * @var Order
     */
    public $modelClass = Order::class;

    /**
     * Renders the index view for the module
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndex()
    {


        $orderStatus = Yii::$app->request->get('order_status', -1);
        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'partialMatchAttributes' => ['sign', 'code', 'name'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_ASC,
            ],
            'pageSize' => $this->pageSize,
            'relations' => [
                'account' => [''],
                'address' => [''],
                'member' => ['username', 'realname', 'mobile', 'email']
            ]
        ]);

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        //订单状态
        if ($orderStatus !== -1)
            $dataProvider->query->andWhere(['=', 'order_status', $orderStatus]);

        // 数据状态
        $dataProvider->query->andWhere(['>=', 'order.status', StatusEnum::DISABLED]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'orderStatus' => OrderStatusEnum::getMap(),
        ]);
    }

    public function actionView()
    {
        $id = Yii::$app->request->get('id', null);

        $model = $this->findModel($id);

        return $this->render($this->action->id, [
            'model' => $model,
        ]);
    }
}
