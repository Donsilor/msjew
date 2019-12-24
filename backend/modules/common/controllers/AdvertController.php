<?php

namespace backend\modules\common\controllers;

use common\models\common\AdvertImages;
use common\models\goods\Type;
use Yii;
use common\models\common\Advert;
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

        //获取产品线
        $type = Type::getDropDown();
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'type' =>$type,
        ]);
    }



    /**
     * ajax编辑/创建
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionAjaxEditLang()
    {
        $id = Yii::$app->request->get('id');
        //$trans = Yii::$app->db->beginTransaction();
        $model = $this->findModel($id);
        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            if($model->save()){
                //多语言编辑
                $this->editLang($model,true);
                return $this->redirect(['index']);
            }else{
                return $this->message($this->getError($model), $this->redirect(['index']), 'error');
            }
        }

        //获取产品线
        $type = Type::getDropDown();
        return $this->renderAjax($this->action->id, [
            'model' => $model,
            'type' => $type,
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
