<?php

namespace common\models\goods;

use Yii;

/**
 * This is the model class for table "{{%goods_ring_lang}}".
 *
 * @property int $id 主键ID
 * @property int $master_id 对接ID
 * @property string $language 语言类型
 * @property string $ring_name 对戒名称
 * @property string $ring_body 图文描述
 * @property string $meta_title meta标题
 * @property string $meta_desc meta描述
 * @property string $meta_word meta关键字
 */
class RingLang extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_ring_lang}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['master_id'], 'integer'],
            [['ring_body'], 'string'],
            [['language'], 'string', 'max' => 5],
            [['ring_name'], 'string', 'max' => 100],
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
            'id' => '主键ID',
            'master_id' => '对接ID',
            'language' => '语言类型',
            'ring_name' => '对戒名称',
            'ring_body' => '图文描述',
            'meta_title' => 'meta标题',
            'meta_desc' => 'meta描述',
            'meta_word' => 'meta关键字',
        ];
    }
}
