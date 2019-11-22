<?php

namespace common\components;

use Yii;
use yii\data\Pagination;
use yii\base\InvalidConfigException;
use common\helpers\ResultHelper;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;

/**
 * Trait Curd
 * @property \yii\db\ActiveRecord|\yii\base\Model $modelClass
 * @package common\components
 */
trait Curd
{
    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if ($this->modelClass === null) {
            throw new InvalidConfigException('"modelClass" 属性必须设置.');
        }
    }

    /**
     * 首页
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $data = $this->modelClass::find()
            ->where(['>=', 'status', StatusEnum::DISABLED]);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $this->pageSize]);
        $models = $data->offset($pages->offset)
            ->orderBy('id desc')
            ->limit($pages->limit)
            ->all();

        return $this->render($this->action->id, [
            'models' => $models,
            'pages' => $pages
        ]);
    }

    /**
     * 编辑/创建
     *
     * @return mixed
     */
    public function actionEdit()
    {
        $id = Yii::$app->request->get('id', null);
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render($this->action->id, [
            'model' => $model,
        ]);
    } 
    

    /**
     * 伪删除
     *
     * @param $id
     * @return mixed
     */
    public function actionDestroy($id)
    {
        if (!($model = $this->modelClass::findOne($id))) {
            return $this->message("找不到数据", $this->redirect(['index']), 'error');
        }

        $model->status = StatusEnum::DELETE;
        if ($model->save()) {
            return $this->message("删除成功", $this->redirect(['index']));
        }

        return $this->message("删除失败", $this->redirect(['index']), 'error');
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
        if ($this->findModel($id)->delete()) {
            return $this->message("删除成功", $this->redirect(['index']));
        }

        return $this->message("删除失败", $this->redirect(['index']), 'error');
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
        if (!$model->save()) {
            return ResultHelper::json(422, $this->getError($model));
        }

        return ResultHelper::json(200, '修改成功');
    }

    /**
     * ajax编辑/创建
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionAjaxEdit()
    {
        $id = Yii::$app->request->get('id');
        $model = $this->findModel($id);

        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            return $model->save()
                ? $this->redirect(['index'])
                : $this->message($this->getError($model), $this->redirect(['index']), 'error');
        }

        return $this->renderAjax($this->action->id, [
            'model' => $model,
        ]);
    }

    /**
     * 返回模型
     *
     * @param $id
     * @return \yii\db\ActiveRecord
     */
    protected function findModel($id)
    {
        /* @var $model \yii\db\ActiveRecord */
        if (empty($id) || empty(($model = $this->modelClass::findOne($id)))) {
            $model = new $this->modelClass;
            return $model->loadDefaultValues();
        }

        return $model;
    }
    
    /**
     * 新增/编辑多语言
     * @param unknown $model
     * @param string $is_ajax
     */
    public function editLang(& $model,$is_ajax = false){
      
        $langModel = $model->langModel();
        $langClassName  = basename($langModel->className());
        $langPosts = Yii::$app->request->post($langClassName);
        if(empty($langPosts)){
            return false;
        }
        foreach ($langPosts as $lang_key=>$lang_post){
            $is_new = true;
            foreach ($model->langs as $langModel){
                if($lang_key == $langModel->language){
                    $langModel->load([$langClassName =>$langPosts[$langModel->language]]);
                    $model->link('langs', $langModel);
                    $is_new = false;
                    break;
                }
            }
            if($is_new == true){
                $langModel = $model->langModel();
                $langModel->load([$langClassName =>$lang_post]);
                $langModel->master_id = $model->id;
                $langModel->language = $lang_key;
                $langModel->save();
            }
        }        
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
      
      return $this->render($this->action->id, [
          'model' => $model,
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
      
      return $this->renderAjax($this->action->id, [
          'model' => $model,
      ]);
    }
}