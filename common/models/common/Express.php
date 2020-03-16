<?php

namespace common\models\common;

use common\models\base\BaseModel;
use Yii;

/**
 * This is the model class for table "common_express".
 *
 * @property int $id
 * @property string $code 快递编码(暂时不用)
 * @property int $status 状态 1启用 0禁用
 * @property int $created_at
 * @property int $updated_at
 */
class Express extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'common_express';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['express_name','sort','cover'], 'safe'],
            [['code'], 'required'],
            [['id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['code'], 'string', 'max' => 25],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => '快递编码',
            'express_name'=>'快递名称',
            'status' => '状态',
            'cover' => '封面',
            'sort' => '排序',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * 语言扩展表
     * @return \common\models\goods\AttributeLang
     */
    public function langModel()
    {
        return new ExpressLang();
    }

    public function getLangs()
    {
        return $this->hasMany(ExpressLang::class,['master_id'=>'id']);

    }

    /**
     * 关联语言一对一
     * @param string $languge
     * @return \yii\db\ActiveQuery
     */
    public function getLang()
    {
        $query = $this->hasOne(ExpressLang::class, ['master_id'=>'id'])->alias('lang')->where(['lang.language' => Yii::$app->params['language']]);
        return $query;
    }
}
