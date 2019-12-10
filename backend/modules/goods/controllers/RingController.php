<?php

namespace backend\modules\goods\controllers;

use common\models\goods\RingRelation;
use common\models\goods\Style;
use services\goods\StyleService;
use Yii;
use common\models\goods\Ring;
use common\components\Curd;
use common\models\base\SearchModel;
use backend\controllers\BaseController;
use common\helpers\ResultHelper;

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
    public $enableCsrfValidation = false;

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
            ->search(Yii::$app->request->queryParams, ['ring_name','language']);
        $this->setLocalLanguage($searchModel->language);
        $dataProvider->query->joinWith(['lang']);
        $dataProvider->query->andFilterWhere(['like', 'lang.ring_name',$searchModel->ring_name]);

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
            $this->editLang($model,true);
            $this->editRingRelation($model);

            return $this->redirect(['index']);
        }
        $style_ids = array();
        if($id){
            $styleService = new StyleService();
            $style_ids = $styleService->getStyleIdsByRing($id);

        }
        return $this->render($this->action->id, [
            'model' => $model,
            'style_ids' => $style_ids,

        ]);
    }

    public function editRingRelation(&$model){
        $relationModel = new RingRelation();
        $relationClassName  = basename($relationModel->className());
        $relationPosts = Yii::$app->request->post($relationClassName);
//       print_r($relationPosts);exit();
        if(empty($relationPosts)){
            return false;
        }
        if(isset($relationPosts['style_id']) && is_array($relationPosts['style_id'])){
            $style_ids = $relationPosts['style_id'];

            $relationModel::deleteAll(['ring_id'=>$model->id]);
            foreach ($style_ids as $val){
                $relationModel = new RingRelation();
                $relationModel->style_id = $val;
                $relationModel->ring_id = $model->id;
                $r = $relationModel->save();
                var_dump($r);
            }
        }

    }


    public function actionSelectStyle()
    {

        $request = Yii::$app->request;
        if($request->isPost)
        {
            $post = Yii::$app->request->post();
            if(!isset($post['style_id']) || empty($post['style_id'])){
                return ResultHelper::json(422, '请选择商品');
            }
            return ResultHelper::json(200, '保存成功',['style_id'=>$post['style_id']]);
        }


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


    public function actionGetStyle(){
        $request = Yii::$app->request;

        if($request->isPost)
        {
            $post = Yii::$app->request->post();
//            return ResultHelper::json(200, '保存成功',['model'=>$post]);
            if(!isset($post['style_id']) || empty($post['style_id'])){
                return ResultHelper::json(422, '参数错误');
            }
            $style_id = $post['style_id'];
            $styleService = new StyleService();
            $model = $styleService->getStyle($style_id);
            $data['id'] = $model['id'];
            $data['style_name'] = $model['style_name'];
            $data['style_sn'] = $model['style_sn'];
            $data['sale_price'] = $model['sale_price'];
            $data['goods_storage'] = $model['goods_storage'];


            return ResultHelper::json(200, '保存成功',$data);
        }

    }


    /**
     * 删除
     *
     * @param $id
     * @return mixed
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->delete()) {
            if(method_exists($model,'langModel')){
                $langModel = $model->langModel();
                $langModel->deleteAll(['in','master_id',[$id]]);

                $relationsModel = $model->getRelations();
                $relationsModel->deleteAll(['in','ring_id',[$id]]);
            }
            return $this->message("删除成功", $this->redirect(['index']));
        }

        return $this->message("删除失败", $this->redirect(['index']), 'error');
    }




}
