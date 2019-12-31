<?php


namespace backend\modules\common\controllers;

use Yii;
use backend\controllers\BaseController;
use common\components\Curd;
use common\enums\AppEnum;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use common\models\common\Config;
use common\models\common\Currency;
use yii\web\NotFoundHttpException;

class CurrencyController extends BaseController
{
    use Curd;

    /**
     * @var \yii\db\ActiveRecord
     */
    public $modelClass = Currency::class;

    /**
     * 首页
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'partialMatchAttributes' => ['sign', 'code', 'name'], // 模糊查询
//            'defaultOrder' => [
//                'cate_id' => SORT_ASC,
//                'sort' => SORT_ASC,
//            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
//            ->andWhere(['app_id' => AppEnum::BACKEND])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'cateDropDownList' => Yii::$app->services->configCate->getDropDown(AppEnum::BACKEND)
        ]);
    }
}