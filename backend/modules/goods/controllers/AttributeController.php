<?php

namespace backend\modules\goods\controllers;

use Yii;
use common\models\goods\Attribute;
use common\components\Curd;
use common\models\base\SearchModel;
use backend\controllers\BaseController;
use common\models\goods\AttributeValue;

/**
* Attribute
*
* Class AttributeController
* @package backend\modules\goods\controllers
*/
class AttributeController extends BaseController
{
    use Curd;

    /**
    * @var Attribute
    */
    public $modelClass = Attribute::class;


    /**
    * 首页
    *
    * @return string
    * @throws \yii\web\NotFoundHttpException
    */
    public function actionIndex()
    {
    	//Yii::$app->language = 'zh-TW';
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
            ->search(Yii::$app->request->queryParams,['attr_name']);
        
        $dataProvider->query->andWhere(['>','status',-1]); 
        $dataProvider->query->joinWith(['lang']);
        $dataProvider->query->with(['type']);
        $dataProvider->query->andFilterWhere(['like', 'lang.attr_name',$searchModel->attr_name]) ;
       
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
              
              //更新属性值到attribute_lang.attr_values;
              Yii::$app->services->attribute->updateAttrValues($model->id);              
              return $this->redirect(['index']);
        }
        
        $dataProvider = null;
        if(isset($id)){
            $searchModel = new SearchModel([
                'model' => AttributeValue::class,
                'scenario' => 'default',
                'partialMatchAttributes' => [], // 模糊查询
                'defaultOrder' => [
                    'id' => SORT_DESC
                ],
                'pageSize' => $this->pageSize,
            ]);
            
            $dataProvider = $searchModel
              ->search(Yii::$app->request->queryParams);
              
            $dataProvider->query->andWhere(['attr_id'=>$id]);
            $dataProvider->query->andWhere(['<>','status',-1]);
            
            $dataProvider->query->with(['lang'=>function($query){
               $query->where(['language'=>Yii::$app->language]);
            }]);
            
            $dataProvider->setSort(false);
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
