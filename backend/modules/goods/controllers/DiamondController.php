<?php

namespace backend\modules\goods\controllers;

use common\enums\AttrIdEnum;
use common\helpers\ArrayHelper;
use common\models\goods\Style;
use Yii;
use common\models\goods\Diamond;
use common\components\Curd;
use common\models\base\SearchModel;
use backend\controllers\BaseController;
use common\helpers\ResultHelper;
use yii\base\Exception;

/**
* Diamond
*
* Class DiamondController
* @package backend\modules\goods\controllers
*/
class DiamondController extends BaseController
{
    use Curd;

    /**
    * @var Diamond
    */
    public $modelClass = Diamond::class;


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
            ->search(Yii::$app->request->queryParams, ['goods_name','language']);
        $this->setLocalLanguage($searchModel->language);
        $dataProvider->query->joinWith(['lang']);
        $dataProvider->query->andFilterWhere(['like', 'lang.goods_name',$searchModel->goods_name]);
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
        $id = Yii::$app->request->get('id');
        $returnUrl = Yii::$app->request->get('returnUrl',['index']);
        
        $model = $this->findModel($id);
        $status = $model ? $model->status:0;
        if ($model->load(Yii::$app->request->post())) { 
            try{
                $trans = Yii::$app->db->beginTransaction();
                if($model->status == 1 && $status == 0){
                    $model->onsale_time = time();
                }
                if(false === $model->save()){
                    throw new Exception($this->getError($model));
                }

                //同步款式库的状态
                Style::updateAll(['status'=>$model->status],['id'=>$model->style_id]);

                $this->editLang($model);
                //同步裸钻数据到goods
                \Yii::$app->services->diamond->syncDiamondToGoods($model->id);
                
                $trans->commit();
            }catch (Exception $e){
                $trans->rollBack();
                $error = $e->getMessage();
                \Yii::error($error);
                return $this->message("保存失败:".$error, $this->redirect([$this->action->id,'id'=>$model->id]), 'error');
            }
            
            return $this->message("保存成功", $this->redirect($returnUrl), 'success');
        }
        
        return $this->render($this->action->id, [
            'model' => $model,
        ]);
    }
    
    /**
     * 生成商品名称
     * @return string[]|array[]
     */
    public function actionGetGoodsName(){
        $carat = Yii::$app->request->post('carat',null);
        $cert_type_id = Yii::$app->request->post('cert_type',null);
        $shape_id = Yii::$app->request->post('shape',null);
        $color_id = Yii::$app->request->post('color',null);
        $clarity_id = Yii::$app->request->post('clarity',null);

        $carat_str = '';
        if(!empty($carat)){
            $carat_str .= $carat.'ct';
        }
        $languages = Yii::$app->params['languages'];
        $ids = array($cert_type_id,$shape_id,$color_id,$clarity_id);
        $data = array();
        foreach ($languages as $key=>$val){
            $goods_name = $carat_str;
            $language = $key;
            $attr_arr = \Yii::$app->services->goodsAttribute->getAttributeByValueIds($ids, $language);
            if(isset($attr_arr[AttrIdEnum::SHAPE])){
                $goods_name .= ' '.$attr_arr[AttrIdEnum::SHAPE];
            }
            if(isset($attr_arr[AttrIdEnum::COLOR])){
                $goods_name .= ' '.$attr_arr[AttrIdEnum::COLOR].'色';
            }
            if(isset($attr_arr[AttrIdEnum::CLARITY])){
                $goods_name .= ' '.$attr_arr[AttrIdEnum::CLARITY].'净度';
            }
            if(isset($attr_arr[AttrIdEnum::CERT_TYPE])){
                $goods_name .= ' '.$attr_arr[AttrIdEnum::CERT_TYPE];
            }
            $data[$language] = $goods_name;
        }

        return ResultHelper::json(200, '保存成功',$data);

    }

    /**
     * ajax更新排序/状态
     *
     * @param $id
     * @return array
     */
    public function actionAjaxUpdate($id)
    {
        if (!($model = $this->modelClass::findOne($id))) {
            return ResultHelper::json(404, '找不到数据');
        }

        $model->attributes = ArrayHelper::filter(Yii::$app->request->get(), ['sort', 'status']);
        if (!$model->save(false)) {
            return ResultHelper::json(422, $this->getError($model));
        }else{
            Style::updateAll(['status'=>$model->status],['id'=>$model->style_id]);
        }
        return ResultHelper::json(200, '修改成功');
    }
}
