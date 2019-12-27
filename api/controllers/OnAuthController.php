<?php

namespace api\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use common\enums\StatusEnum;
use common\helpers\ResultHelper;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;

/**
 * 需要授权登录访问基类
 *
 * Class OnAuthController
 * @package api\controllers
 * @property yii\db\ActiveRecord|yii\base\Model $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class OnAuthController extends ActiveController
{
    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        // 注销系统自带的实现方法
        unset($actions['index'], $actions['update'], $actions['create'], $actions['view'], $actions['delete']);
        // 自定义数据indexDataProvider覆盖IndexAction中的prepareDataProvider()方法
        // $actions['index']['prepareDataProvider'] = [$this, 'indexDataProvider'];
        return $actions;
    }

    /**
     * 验证更新是否本人
     *
     * @param $action
     * @return bool
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function beforeAction($action)
    {
        parent::beforeAction($action);

        if (in_array($action, ['create','update','delete','view'])) {
            throw new NotFoundHttpException('权限不足.');
        }        
        return true;
    }
    
    /**
     * 查询基本信息
     * @return mixed|NULL|\yii\db\ActiveRecord
     */
    protected function info($fields = [])
    {
        $id = \Yii::$app->request->get('id');        
        if(!$id) {
            throw new UnprocessableEntityHttpException("id不能为空");
        }
        
        $model = $this->findModel($id,$fields);
        return $model;
    }
    /**
     * 添加
     * @param unknown $data
     * @throws UnprocessableEntityHttpException
     * @return unknown
     */
    protected function add($allows = null,$data = null)
    {
        $data = $data??\Yii::$app->request->post();
        $model = new $this->modelClass;
        $model->attributes = $data;
        $model->member_id  = $this->member_id;
        if(false === $model->save(true,$allows)){
            throw new UnprocessableEntityHttpException($this->getError($model));
        }
        return $model;
    }
    
    /**
     * 编辑
     * @param unknown $data
     * @param unknown $allows
     * @return mixed|NULL|array
     */
    protected function edit($allows = null, $data = null)
    {
        
        $id = \Yii::$app->request->post('id');        
        if(!$id) {
            throw new UnprocessableEntityHttpException("id不能为空");
        }
        
        $data = $data ?? \Yii::$app->request->post();
        $model = $this->findModel($id);
        $model->attributes = $data;
        
        if(false === $model->save(true,$allows)){
            throw new UnprocessableEntityHttpException($this->getError($model));
        }
        return  $model;
    }
    /**
     * 删除
     * @param unknown $id
     * @param unknown $allows
     * @return mixed|NULL|unknown[]
     */
    protected function del()
    {
        $ids = \Yii::$app->request->post('id');
        if(!$ids) {
            throw new UnprocessableEntityHttpException("id不能为空");
        }
        if(!is_array($ids)) {
            $ids = explode(",",$ids);
        }
        return $this->modelClass::deleteAll(['member_id'=>$this->member_id,'id'=>$ids]);
    }
    
    /**
     * @param $id
     * @return \yii\db\ActiveRecord
     * @throws NotFoundHttpException
     */
    protected function findModel($id, $fields = null)
    {

        /* @var $model \yii\db\ActiveRecord */
        if (empty($id) || !($model = $this->modelClass::find()->select($fields)->where([
                'id' => $id,'member_id'=>$this->member_id
            ])->one())) {
            throw new NotFoundHttpException('请求的数据不存在');
        }

        return $model;
    }
    
    /**
     * 查询列表
     * @param array $fields
     * @throws NotFoundHttpException
     * @return unknown
     */
    protected function findModels($fields = null)
    {
        /* @var $model \yii\db\ActiveRecord */
        if (!($models = $this->modelClass::find()->select($fields)->where([
                'member_id'=>$this->member_id
        ])->all())) {
            throw new NotFoundHttpException('请求的数据不存在');
        }
        
        return $models;
    }
}
