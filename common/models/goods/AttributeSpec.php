<?php

namespace common\models\goods;

use Yii;
use common\models\base\BaseModel;

/**
 * This is the model class for table "{{%goods_category_spec}}".
 *
 * @property int $id 主键
 * @property int $type_id 产品线ID
 * @property int $attr_id 属性ID
 * @property int $attr_type 分类类型(1-基础属性,2-销售属性,3-定制属性)
 * @property string $attr_values 属性值ID
 * @property int $input_type 属性输入框类型(1-输入框,2-下拉框,3-单选,4-多选)
 * @property int $is_require 是否必填(1-是,0-否)
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $sort 排序字段
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class AttributeSpec extends BaseModel
{
    public $attr_name;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_attribute_spec}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type_id', 'attr_id', 'attr_type', 'input_type', 'is_require', 'status'], 'required'],
            [['type_id', 'attr_id', 'attr_type', 'input_type', 'is_require','is_show' ,'status', 'sort', 'created_at', 'updated_at'], 'integer'],
            //[['attr_values'], 'string', 'max' => 500],
            [['attr_id'],'unique', 'targetAttribute'=>['type_id','attr_id'],
              //'targetClass' => '\models\Dishes', // 模型，缺省时默认当前模型。
              'comboNotUnique' => '当前产品线已添加过该属性' //错误信息
            ],
            [['attr_values'],'implodeArray','params'=>['split'=>',']],
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
            'type_id' => Yii::t('goods_attribute', '产品线'),
            'attr_id' => Yii::t('goods_attribute', '属性'),
            'attr_type' => Yii::t('goods_attribute', '属性类型'),
            'attr_values' => Yii::t('goods_attribute', '属性值'),
            'input_type' => Yii::t('goods_attribute', '显示类型'),
            'is_require' => Yii::t('goods_attribute', '必填'),
            'is_show' => Yii::t('goods_attribute', '前端显示'),
            'status' => Yii::t('goods_attribute', '状态'),
            'sort' => Yii::t('goods_attribute', '排序'),
            'created_at' => Yii::t('goods_attribute', '创建时间'),
            'updated_at' => Yii::t('goods_attribute', '更新时间'),
            'attr_name'=>  Yii::t('goods_attribute', '属性名称'),
        ];
    }
    
    /**
     * 属性语言一对一
     * @return \yii\db\ActiveQuery
     */
    public function getAttr()
    {
        return $this->hasOne(AttributeLang::class, ['master_id'=>'attr_id'])->alias('attr')->where(['attr.language'=>Yii::$app->params['language']]);
    }
    /**
     * 关联产品线一对一
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(TypeLang::class, ['master_id'=>'type_id'])->alias('type')->where(['type.language'=>Yii::$app->params['language']]);
    }
}
