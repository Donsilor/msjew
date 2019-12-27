<?php

namespace api\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use common\enums\StatusEnum;

/**
 * 个人信息访问基类
 *
 * 注意：适用于个人中心
 *
 * Class UserAuthController
 * @package api\controllers
 * @property yii\db\ActiveRecord|yii\base\Model $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class UserAuthController extends OnAuthController
{
    /**
     * @param $id
     * @return \yii\db\ActiveRecord
     * @throws NotFoundHttpException
     */
    protected function findModel($id,$fields = null)
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
    protected function findModels($fields = [])
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
