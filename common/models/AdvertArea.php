<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "common_advert_area".
 *
 * @property int $adv_id 广告图片
 * @property int $area_id
 * @property int $adv_image_id
 */
class AdvertArea extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'common_advert_area';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['adv_id', 'adv_image_id'], 'required'],
            [['adv_id', 'area_id', 'adv_image_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'adv_id' => '广告图片',
            'area_id' => 'Area ID',
            'adv_image_id' => 'Adv Image ID',
        ];
    }
}
