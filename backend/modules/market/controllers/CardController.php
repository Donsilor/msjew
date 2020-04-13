<?php

namespace backend\modules\market\controllers;

use backend\controllers\BaseController;
use backend\modules\market\forms\CardFrom;
use common\components\Curd;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use common\models\market\MarketCard;
use common\models\market\MarketCardDetails;
use common\models\order\Order;
use services\market\CardService;
use yii\web\Controller;
use yii\web\UnprocessableEntityHttpException;

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
        $id = \Yii::$app->request->get('id', null);

        if(empty($id)) {
            exit;
        }

        $searchModel = new SearchModel([
            'model' => MarketCardDetails::class,
            'scenario' => 'default',
            'partialMatchAttributes' => [], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize,
            'relations' => [
                'card' => ['id'],
            ]
        ]);
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        $dataProvider->query->andWhere(['market_card.id'=>$id]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * ajax编辑/创建
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionAjaxEdit()
    {
        $returnUrl = \Yii::$app->request->get('returnUrl',['index']);
        $model = new CardFrom();

        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(\Yii::$app->request->post())) {

            if(!\Yii::$app->cache->delete('actionAjaxEdit-'.\Yii::$app->getUser()->id)) {
                return $this->message('请刷新表单后重新提交', $this->redirect($returnUrl), 'error');
            }

            if(!$model->validate()) {
                return $this->message($this->getError($model), $this->redirect($returnUrl), 'error');
            }

            $trans = \Yii::$app->db->beginTransaction();

            try {
                \Yii::$app->services->card->generateCards($model->toArray(), $model->count);

                $trans->commit();

            } catch (\Exception $exception) {
                $trans->rollBack();

            }

            $this->redirect($returnUrl);
        }

        \Yii::$app->cache->set('actionAjaxEdit-'.\Yii::$app->getUser()->id, true);

        return $this->renderAjax($this->action->id, [
            'model' => $model,
        ]);
    }
}
