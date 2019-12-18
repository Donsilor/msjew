<?php

namespace backend\modules\goods\controllers;

use Yii;
use common\models\goods\Style;
use common\components\Curd;
use common\models\base\SearchModel;

use backend\controllers\BaseController;
use yii\base\Exception;


/**
* Style
*
* Class StyleController
* @package backend\modules\goods\controllers
*/
class StyleController extends BaseController
{
    use Curd;

    /**
    * @var Style
    */
    public $modelClass = Style::class;


    /**
    * 首页
    *
    * @return string
    * @throws \yii\web\NotFoundHttpException
    */
    public function actionIndex()
    {
        $type_id = Yii::$app->request->get('type_id',0);
        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'partialMatchAttributes' => [], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);
        $typeModel = Yii::$app->services->goodsType->getAllTypesById($type_id,null);
        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams,['style_name','language']);
        //切换默认语言
        $this->setLocalLanguage($searchModel->language);
        if($typeModel){
            $dataProvider->query->andFilterWhere(['in', 'type_id',$typeModel['ids']]);
        }
        $dataProvider->query->joinWith(['lang']);
        $dataProvider->query->andFilterWhere(['like', 'lang.style_name',$searchModel->style_name]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,  
            'typeModel'  =>$typeModel,  
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
        $type_id = Yii::$app->request->get('type_id', 0);
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            $trans = Yii::$app->db->beginTransaction();
            try{                
                if(false === $model->save()){
                    throw new Exception(current($model->getFirstErrors()));
                }
                $id = $model->id;
                $this->editLang($model);
                
                $trans->commit();                
            }catch (Exception $e){
                $trans->rollBack();
                $error = $e->getMessage();
                \Yii::error($error);
                return $this->message("保存失败", $this->redirect([$this->action->id,'id'=>$id,'type_id'=>$type_id]), 'error');
            }
            
            //商品更新
            \Yii::$app->services->goods->createGoods($id);
            return $this->message("保存成功", $this->redirect(['index','type_id'=>$type_id]), 'success');
        }
        return $this->render($this->action->id, [
                'model' => $model,
        ]);
    }
    
    public function actionTest()
    {
        $style_id = Yii::$app->request->get("style_id");
        Yii::$app->services->goods->createGoods($style_id);
    }
    
}
