<?php

namespace common\models\setting;

use Yii;

/**
 * This is the model class for table "{{%advert_lang}}".
 *
 * @property int $id 主键
 * @property int $master_id 类型(1-WEB端,2-移动端)
 * @property string $adv_name 广告位名称
 * @property string $remark 广告位描述
 */
class AdvertLang extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%advert_lang}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['adv_name'], 'required'],
            [['master_id'], 'integer'],
            [['adv_name'], 'string', 'max' => 100],
            [['remark'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'master_id' => '类型(1-WEB端,2-移动端)',
            'adv_name' => '名称',
            'remark' => '广告位描述',
        ];
    }


}
