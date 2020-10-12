<?php

namespace backend\modules\market\controllers;

use backend\controllers\BaseController;
use backend\modules\market\forms\CardFrom;
use common\components\Curd;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use common\models\market\MarketCard;
use common\models\market\MarketCardDetails;
use common\models\market\MarketCardGoodsType;
use common\models\order\Order;
use services\market\CardService;
use yii\db\Expression;
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
        $search = \Yii::$app->request->get('SearchModel');

        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'partialMatchAttributes' => [], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize,
            'relations' => [
                'user' => ['username']
            ]
        ]);
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams,['goods_type_attach','status','created_at', 'max_use_time']);

        if(!empty($search['goods_type_attach'])) {
            $query = MarketCardGoodsType::find()->where(['goods_type'=>$search['goods_type_attach']])->select(['batch']);
            $dataProvider->query->andWhere(['batch'=>$query]);
        }

        if(!empty($search['status'])) {
            $status = (int)$search['status'];
            $where = ['and'];
            if($status===4) {
                $where[]['balance'] = 0;
                $where[] = ['not in','market_card.id', MarketCardDetails::find()->select(['card_id'])->where(['status'=>2, 'type'=>2])];
            }
            else if($status===3) {
                $where[] = ['>', 'balance' ,0];
                $where[] = ['<=', 'end_time' ,time()];
            }
            else if($status===2) {
                $where[] = [
                    'or',
                    ['>', 'balance' ,0],
                    ['in','market_card.id', MarketCardDetails::find()->select(['card_id'])->where(['status'=>2, 'type'=>2])]
                ];
                $where[] = ['>', 'end_time' ,time()];
                $where[] = ['<>', 'balance' ,new Expression('amount')];
            }
            else if($status===1) {
                $where[] = ['>', 'balance' ,0];
                $where[] = ['>', 'end_time' ,time()];
                $where[] = ['=', 'balance' ,new Expression('amount')];
            }
            $dataProvider->query->andWhere($where);
        }

        //最大使用时长
        if(isset($search['max_use_time']) && strlen($search['max_use_time'])>0) {
            if($search['max_use_time']) {
                $dataProvider->query->andWhere(['<>', 'max_use_time', 0]);
            }
            else {
                $dataProvider->query->andWhere(['=', 'max_use_time', 0]);
            }
        }

        //创建时间过滤
        if (!empty(\Yii::$app->request->queryParams['SearchModel']['created_at'])) {
            list($start_date, $end_date) = explode('/', \Yii::$app->request->queryParams['SearchModel']['created_at']);
            $dataProvider->query->andFilterWhere(['between', 'market_card.created_at', strtotime($start_date), strtotime($end_date) + 86400]);
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionView()
    {
        $id = \Yii::$app->request->get('id', null);

        if(!($cardModel = MarketCard::findOne($id))) {
            exit;
        }

        $searchModel = new SearchModel([
            'model' => MarketCardDetails::class,
            'scenario' => 'default',
            'partialMatchAttributes' => [], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => 1000,
        ]);
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        $dataProvider->query->andWhere(['card_id'=>$id]);
        $dataProvider->query->andWhere(['<>', 'type', 1]);

        return $this->render($this->action->id, [
            'cardModel' => $cardModel,
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

            ignore_user_abort(true);
            set_time_limit(900);

            $trans = \Yii::$app->db->beginTransaction();

            try {
                \Yii::$app->services->card->generateCards($model->toArray(), $model->count);

                $trans->commit();

            } catch (\Exception $exception) {

                $trans->rollBack();
                return $this->message($exception->getMessage(), $this->redirect($returnUrl), 'error');
            }

            $this->redirect($returnUrl);
        }

        \Yii::$app->cache->set('actionAjaxEdit-'.\Yii::$app->getUser()->id, true);

        return $this->renderAjax($this->action->id, [
            'model' => $model,
        ]);
    }
}
