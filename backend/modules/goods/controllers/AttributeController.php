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

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
    
    /**
     * 编辑/创建
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionAjaxEdit()
    {
    	$id = Yii::$app->request->get('id', '');
    	$model = $this->findModel($id);
    	    	
    	// ajax 校验
    	$this->activeFormValidate($model);
    	if ($model->load(Yii::$app->request->post())) {
    		return $model->save()
    		? $this->redirect(['index', 'id' => $model->id])
    		: $this->message($this->getError($model), $this->redirect(['index', 'id' => $model->id]), 'error');
    	}
    	return $this->renderAjax('ajax-edit', [
    			'model' => $model,
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
