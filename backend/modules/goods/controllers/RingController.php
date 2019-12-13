<?php

namespace backend\modules\goods\controllers;

use common\enums\StatusEnum;
use common\models\goods\RingRelation;
use common\models\goods\Style;
use services\goods\StyleService;
use Yii;
use common\models\goods\Ring;
use common\components\Curd;
use common\models\base\SearchModel;
use backend\controllers\BaseController;
use common\helpers\ResultHelper;
use yii\db\Exception;
use common\helpers\ArrayHelper;

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
            $this->editRingRelation($model);
            $this->editLang($model,true);

            return $this->redirect(['index']);
        }
        $style_ids = array();
        if($id){
            $ring_relations = Yii::$app->services->goodsStyle->getRelationByRing($id);
            $style_ids = array_column($ring_relations,'style_id');
        }

        return $this->render($this->action->id, [
            'model' => $model,
            'style_ids' => $style_ids,

        ]);
    }
    public function editRingRelation2($ring_id)
    {  
        
        $relationClassName  = basename(RingRelation::className());
        $posts = Yii::$app->request->post($relationClassName);        
            
        if(!empty($posts['style_id']) && is_array($posts['style_id'])){              
            try{
                $style_ids = $posts['style_id'];  
                //要删除的数据
                $delRows = RingRelation::find()->where(['ring_id'=>$ring_id,['not in','style_id',$style_ids]])->asArray()->all();
                if(!empty($delRows)){
                    $del_style_ids = array_column($delRows, 'style_id');
                    //更新款式锁定状态
                    //Style::updateAll(['is_lock'=>0],['style_id'=>$del_style_ids]);
                    RingRelation::deleteAll(['ring_id'=>$ring_id,['not in','style_id',$del_style_ids]]);
                }
                //数据入库
                foreach ($style_ids as $style_id){
                    $relationModel = RingRelation::find()->where(['ring_id'=>$ring_id,'style_id'])->count();
                    if(!$relationModel){
                        $relationModel = new RingRelation();
                        $relationModel->ring_id  = $ring_id;
                        $relationModel->style_id = $style_id;
                        $relationModel->save(false);
                    }
                }
                
            }catch (Exception $e){
                throw $e;
            }            
        }        
    }
    public function editRingRelation(&$model){
        $relationModel = new RingRelation();
        $styleModel = new Style();
        $relationClassName  = basename($relationModel->className());
        $relationPosts = Yii::$app->request->post($relationClassName);
//       print_r($relationPosts);exit();
        if(empty($relationPosts)){
            return false;
        }

        try{
            $trans = Yii::$app->db->beginTransaction();
            if(isset($relationPosts['style_id']) && is_array($relationPosts['style_id'])){

                $style_ids = $relationPosts['style_id'];
                $relation_list = Yii::$app->services->goodsStyle->getRelationByRing($model->id);
                $del_relation_ids = array();
                $update_style_lock = array();
                foreach ($relation_list as $key => $val){
                    $style_id = $val['style_id'];
                    if(in_array($style_id,$style_ids)){
                        $style_ids = array_flip($style_ids);
                        unset($style_ids[$style_id]);
                        $style_ids = array_flip($style_ids);
                        continue;
                    }
                    $del_relation_ids[] = $val['id'];
                    $update_style_lock[] = $style_id;

                }

                //解绑去掉的商品
                if(!empty($del_relation_ids)){
                    $res1 = $relationModel::deleteAll(['in','id',$del_relation_ids]);
                    if(!$res1){
                        $trans->rollBack();
                        $this->message("原有商品解绑失败", $this->redirect(['index']), 'error');
                        return;
                    }
                }

                //给此对戒解绑的商品解锁
                if(!empty($update_style_lock)){
                    $res2 = $styleModel::updateAll(['is_lock'=>0],['in','id', $update_style_lock]);
                    if(!$res2){
                        $trans->rollBack();
                        $this->message("原有商品解锁失败", $this->redirect(['index']), 'error');
                        return;
                    }
                }

                if(!empty($style_ids)){
                    //给添加的商品上锁
                    $res3 = $styleModel::updateAll(['is_lock'=>1],['and',['in','id', $style_ids],['is_lock'=>0]]);
                    if(!$res3){
                        $trans->rollBack();
                        $this->message("新增商品已经绑定", $this->redirect(['index']), 'error');
                        return;
                    }

                    //绑定添加的商品
                    foreach ($style_ids as $val){
                        $relationModel = new RingRelation();
                        $relationModel->style_id = $val;
                        $relationModel->ring_id = $model->id;
                        $res4 = $relationModel->save();
                        if(!$res4){
                            $trans->rollBack();
                            $this->message("新增商品添加失败", $this->redirect(['index']), 'error');
                            return;
                        }
                    }
                }

            }
            $trans->commit();


        } catch (Exception $e) {
            $trans->rollBack();
            $this->message("商品添加失败", $this->redirect(['index']), 'error');
            return;
        }


    }


    //添加商品时查询戒指数据
    public function actionSelectStyle()
    {

        $request = Yii::$app->request;
        if($request->isPost)
        {
            $post = Yii::$app->request->post();
            if(!isset($post['style_id']) || empty($post['style_id'])){
                return ResultHelper::json(422, '请选择商品');
            }else{
                $style_id = $post['style_id'];
            }
            return ResultHelper::json(200, '保存成功',['style_id'=>$style_id]);
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
        $dataProvider->query->andWhere(['>=', 'status', StatusEnum::DISABLED]);
       //戒指分类
        $dataProvider->query->andFilterWhere(['=', 'type_id',2]);
        $dataProvider->query->andFilterWhere(['=', 'is_lock',0]);

        $dataProvider->query->joinWith(['lang']);

        $dataProvider->query->andFilterWhere(['like', 'lang.style_name',$searchModel->style_name]);
        return $this->render('select-style', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    //编辑时获取单个戒指数据
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
            $model = Yii::$app->services->goodsStyle->getStyle($style_id);
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
