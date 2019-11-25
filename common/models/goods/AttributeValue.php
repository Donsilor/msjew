<?php

namespace common\models\goods;

use Yii;
use common\models\base\BaseModel;

/**
 * This is the model class for table "{{%goods_attribute_value}}".
 *
 * @property int $id 主键
 * @property int $attr_id
 * @property int $sort 属性排序(数字越小越前)
 * @property int $status 状态(-1删除,0禁用,1-正常)
 * @property int $created_at 创建时间
 * @property int $updated_at
 */
class AttributeValue extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_attribute_value}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['attr_id', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('goods_attribute', 'ID'),
            'attr_id' => Yii::t('goods_attribute', 'Attr ID'),
            'sort' => Yii::t('goods_attribute', 'Sort'),
            'status' => Yii::t('goods_attribute', 'Status'),
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
      return new AttributeValueLang();
    }
    /**
     * 关联语言一对多
     * @return \yii\db\ActiveQuery
     */
    public function getLangs()
    {
      return $this->hasMany(AttributeValueLang::class,['master_id'=>'id']);
      
    }
    /**
     * 关联语言一对一
     * @return \yii\db\ActiveQuery
     */
    public function getLang()
    {
      return $this->hasOne(AttributeValueLang::class, ['master_id'=>'id']);
    }
}
