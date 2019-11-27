<?php

namespace backend\modules\setting\controllers;

use common\models\setting\AdvertImages;
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
                'id' => SORT_ASC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams, ['adv_name']);
        $dataProvider->query->joinWith(['lang']);
        $dataProvider->query->andWhere(['>','status',-1]);


        $dataProvider->query->andFilterWhere(['like', 'lang.adv_name',$searchModel->adv_name]) ;

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }


    /**
     * 编辑/创建 多语言
     *
     * @return mixed
     */
    public function actionEditLang()
    {
        $id = Yii::$app->request->get('id', null);
        //$trans = Yii::$app->db->beginTransaction();
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->editLang($model,false);
            return $this->redirect(['index']);
        }

        $dataProvider = null;
        if(isset($id)){
            $searchModel = new SearchModel([
                'model' => AdvertImages::class,
                'scenario' => 'default',
                'partialMatchAttributes' => [], // 模糊查询
                'defaultOrder' => [
                    'id' => SORT_ASC
                ],
                'pageSize' => $this->pageSize,
            ]);

            $dataProvider = $searchModel
                ->search(Yii::$app->request->queryParams);

            $dataProvider->query->andWhere(['adv_id'=>$id]);
            $dataProvider->query->andWhere(['<>','status',-1]);

        }
        return $this->render($this->action->id, [
            'model' => $model,
            'dataProvider'=>$dataProvider,
        ]);
    }

    /**
     * 删除
     * @param unknown $id
     * @return mixed|string
     */
    public function actionDelete($id)
    {
        if ($model = $this->findModel($id)) {
            $model->status = -1;
            $model->save();
            return $this->message("删除成功", $this->redirect(['index', 'id' => $model->id]));
        }

        return $this->message("删除失败", $this->redirect(['index', 'id' => $model->id]), 'error');
    }
}
