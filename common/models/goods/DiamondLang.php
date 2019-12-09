<?php

namespace common\models\goods;

use Yii;

/**
 * This is the model class for table "{{%goods_diamond_lang}}".
 *
 * @property int $id 商品公共表id
 * @property int $master_id 款式ID
 * @property string $language 语言类型
 * @property string $goods_name 款式名称
 * @property string $goods_body
 * @property string $mobile_body 手机端商品描述
 * @property string $meta_title SEO标题
 * @property string $meta_word SEO关键词
 * @property string $meta_desc SEO描述
 */
class DiamondLang extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_diamond_lang}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[],'required'],
            [['master_id'], 'integer'],
            [['goods_body', 'mobile_body'], 'string'],
            [['language'], 'string', 'max' => 5],
            [['goods_name'], 'string', 'max' => 50],
            [['meta_title', 'meta_word'], 'string', 'max' => 200],
            [['meta_desc'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '商品公共表id',
            'master_id' => '款式ID',
            'language' => '语言类型',
            'goods_name' => '款式名称',
            'goods_body' => Yii::t('goods_diamond_lang', 'Goods Body'),
            'mobile_body' => '手机端商品描述',
            'meta_title' => 'SEO标题',
            'meta_word' => 'SEO关键词',
            'meta_desc' => 'SEO描述',
        ];
    }
}
