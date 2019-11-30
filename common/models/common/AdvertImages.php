<?php

namespace common\models\common;

use Yii;

/**
 * This is the model class for table "{{%advert_images}}".
 *
 * @property int $id 主键
 * @property int $adv_id 广告关联表
 * @property string $adv_image 图片地址
 * @property string $adv_url 链接地址
 * @property string $start_time 开始时间
 * @property string $end_time 结束时间
 */
class AdvertImages extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%common_advert_images}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['adv_image'], 'required'],
            [['adv_id','status','sort'], 'integer'],
            [['start_time', 'end_time','start_end','adv_name','title'], 'safe'],
            [['adv_image'], 'string', 'max' => 200],
            [['adv_url'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'adv_id' => '广告位置',
            'adv_image' => '图片地址',
            'adv_url' => '链接地址',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'updated_at'=> '更新时间',
            'status' => '是否启用',
            'sort' => '排序',
            'title' => '描述',
        ];
    }



    /**
     * 语言扩展表
     * @return \common\models\goods\AttributeLang
     */
    public function langModel()
    {
        return new AdvertImagesLang();
    }

    public function getLangs()
    {
        return $this->hasMany(AdvertImagesLang::class,['master_id'=>'id']);

    }

    /**
     * 关联语言一对一
     * @param string $languge
     * @return \yii\db\ActiveQuery
     */
    public function getLang()
    {
        $query = $this->hasOne(AdvertImagesLang::class, ['master_id'=>'id'])->alias('lang')->where(['lang.language' => Yii::$app->language]);
        return $query;
    }

    /**
     * 关联分类
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCate()
    {
        return $this->hasOne(Advert::class, ['id' => 'adv_id']);
    }
}
