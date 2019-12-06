<?php

namespace backend\modules\goods\controllers;

use common\models\goods\Style;
use Yii;
use common\models\goods\Ring;
use common\components\Curd;
use common\models\base\SearchModel;
use backend\controllers\BaseController;

/**
* Ring
*
* Class RingController
* @package backend\modules\goods\controllers
*/
class RingController extends BaseController
{
    use Curd;

    /**
    * @var Ring
    */
    public $modelClass = Ring::class;


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



    /**
     * 编辑/创建 多语言
     *
     * @return mixed
     */
    public function actionEditLang()
    {
        $id = Yii::$app->request->get('id', null);
        if($post = \Yii::$app->request->post()){
            print_r($post);

        }

        //$trans = Yii::$app->db->beginTransaction();
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            /* print_r($model->toArray());
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->data = \yii\widgets\ActiveForm::validate($model);
            Yii::$app->end();
            exit; */
            echo $model->save();
            $this->editLang($model,false);
            echo 'success';
            exit;
            // return $this->redirect(['index']);
        }
        return $this->render($this->action->id, [
            'model' => $model,
        ]);
    }


    public function actionSelectStyle()
    {


        $searchModel = new SearchModel([
            'model' => Style::class,
            'scenario' => 'default',
            'partialMatchAttributes' => [], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams,['style_name']);

        $dataProvider->query->joinWith(['lang']);
        $dataProvider->query->andFilterWhere(['like', 'lang.style_name',$searchModel->style_name]);
        return $this->render('select-style', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }



}
