<?php

namespace backend\modules\goods\controllers;

use Yii;
use common\models\goods\Attribute;
use common\components\Curd;
use common\models\base\SearchModel;
use backend\controllers\BaseController;

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
    	//Yii::$app->language = 'en-US';
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
        
        $dataProvider->query->with(['lang'=>function($query){
            $query->where(['language'=>Yii::$app->language]);
        }]);
        
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
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
    	if (($model = $this->findModel($id))->delete()) {
    		return $this->message("删除成功", $this->redirect(['index', 'id' => $model->id]));
    	}
    	
    	return $this->message("删除失败", $this->redirect(['index', 'id' => $model->id]), 'error');
    }
}
