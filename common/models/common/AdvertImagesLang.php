<?php

namespace common\models\common;

use Yii;

/**
 * This is the model class for table "{{%advert_images_lang}}".
 *
 * @property int $id 主键
 * @property int $master_id 广告图片
 * @property string $seo_title 广告关联表
 */
class AdvertImagesLang extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_advert_images_lang}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['master_id'], 'integer'],
            [['title','adv_image'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'master_id' => '广告图片',
            'title' => '图片描述',
            'adv_image' => '图片地址',
        ];
    }
}
