<?php

namespace common\models\goods;

use Yii;

/**
 * This is the model class for table "{{%goods_category_lang}}".
 *
 * @property int $id 主键
 * @property int $attr_id
 * @property string $language 语言类型
 * @property string $cat_name 分类名称
 * @property string $remark 备注
 * @property string $meta_title meta标题
 * @property string $meta_desc meta描述
 * @property string $meta_word meta关键词
 */
class TypeLang extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_type_lang}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['master_id'], 'integer'],
            [['language'], 'string', 'max' => 5],
            [['type_name'], 'string', 'max' => 30],
            [['meta_title', 'meta_desc', 'meta_word'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'master_id' => Yii::t('goods_type', 'Attr ID'),
            'language' => '语言类型',
            'type_name' => '分类名称',
            'meta_title' => 'seo标题',
            'meta_desc' => 'seo描述',
            'meta_word' => '关键词',
        ];
    }

    public function findModel($pid)
    {

    }
}
