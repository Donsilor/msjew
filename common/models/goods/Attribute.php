<?php

namespace common\models\goods;

use Yii;
use common\models\base\BaseModel;

/**
 * This is the model class for table "{{%goods_attribute}}".
 *
 * @property int $id 主键
 * @property int $cat_id 分类ID
 * @property int $attr_type 分类类型(1-基础属性,2-销售属性,3-定制属性)
 * @property int $input_type 属性输入框类型(1-输入框,2-下拉框,3-单选,4-多选)
 * @property int $is_require 是否必填(1-是,0-否)
 * @property int $is_system 是否系统配置(1是,0否)
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $sort 排序字段
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class Attribute extends BaseModel
{
    public $attr_name;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_attribute}}';
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'required'],
            [['id','status', 'sort','created_at', 'updated_at'], 'integer'],
            [['attr_name','language'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('goods_attribute', 'ID'),            
            'status' => Yii::t('goods_attribute', '状态'),
            'sort' => Yii::t('goods_attribute', '排序'),
            'attr_type' => Yii::t('goods_attribute', '属性类型'),
            'type_id' => Yii::t('goods_attribute', '产品线'),
            'input_type' => Yii::t('goods_attribute', '显示类型'),
            'is_require' => Yii::t('goods_attribute', '是否必填'),
            'created_at' => Yii::t('goods_attribute', '创建时间'),
            'updated_at' => Yii::t('goods_attribute', '更新时间'),  
                
            'attr_name'=>Yii::t('goods_attribute', '属性名称'),
        ];
    }
    
    /**
     * 语言扩展表
     * @return \common\models\goods\AttributeLang
     */
    public function langModel()
    {
        return new AttributeLang();
    }
    /**
     * 关联语言一对多
     * @return \yii\db\ActiveQuery
     */
    public function getLangs()
    {
        return $this->hasMany(AttributeLang::class,['master_id'=>'id']);
      
    }
    /**
     * 关联语言一对一
     * @return \yii\db\ActiveQuery
     */
    public function getLang()
    {
        return $this->hasOne(AttributeLang::class, ['master_id'=>'id'])->alias('lang')->where(['lang.language'=>Yii::$app->language]);
    }
    /**
     * 关联产品线分类一对一
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(TypeLang::class, ['master_id'=>'type_id'])->alias('type')->where(['type.language'=>Yii::$app->language]);
    }
}
