<?php

namespace common\models\common;

use Yii;

/**
 * This is the model class for table "common_express_lang".
 *
 * @property int $id
 * @property int $master_id 快递id
 * @property string $language 语言代号
 * @property string $express_name 快递名称
 */
class ExpressLang extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'common_express_lang';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['master_id'], 'integer'],
            [['language'], 'string', 'max' => 5],
            [['express_name'], 'string', 'max' => 255],
            [['master_id', 'language'], 'unique', 'targetAttribute' => ['master_id', 'language']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'master_id' => 'Master ID',
            'language' => 'Language',
            'express_name' => '快递名称',
        ];
    }
}
