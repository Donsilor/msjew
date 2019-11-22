<?php

namespace common\models\goods;

use Yii;

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
class Attribute extends \yii\db\ActiveRecord
{
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
        	[['cat_id', 'attr_type', 'input_type'], 'required'],
            [['cat_id', 'attr_type', 'input_type', 'is_require', 'is_system', 'status', 'sort'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('goods_attribute', 'ID'),
            'cat_id' => Yii::t('goods_attribute', 'Cat ID'),
            'attr_type' => Yii::t('goods_attribute', 'Attr Type'),
            'input_type' => Yii::t('goods_attribute', 'Input Type'),
            'is_require' => Yii::t('goods_attribute', 'Is Require'),
            'is_system' => Yii::t('goods_attribute', 'Is System'),
            'status' => Yii::t('goods_attribute', 'Status'),
            'sort' => Yii::t('goods_attribute', 'Sort'),
            'created_at' => Yii::t('goods_attribute', 'Created At'),
            'updated_at' => Yii::t('goods_attribute', 'Updated At'),
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
     * @param string $languge
     * @return \yii\db\ActiveQuery
     */
    public function getLang()
    {
        $query = $this->hasOne(AttributeLang::class, ['master_id'=>'id']);
        return $query;
    }
}
