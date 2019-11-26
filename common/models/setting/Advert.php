<?php

namespace common\models\setting;

use Yii;

/**
 * This is the model class for table "{{%advert}}".
 *
 * @property int $id 主键
 * @property int $adv_type 类型(1-WEB端,2-移动端)
 * @property int $adv_height 图片高
 * @property int $adv_width 图片宽
 * @property string $adv_name 广告位名称
 * @property int $show_type 广告位展示方式（1-可以发布多条并幻灯展示 2-可以發佈多条廣告并随机展示 3-只允许發佈并展示一条廣告）
 * @property int $open_type 是否新窗口打开（1-是，2-否）
 * @property string $remark 广告位描述
 * @property int $status 是否启用（1-是，2-否）
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class Advert extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%advert}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['adv_type', 'adv_height', 'adv_width', 'show_type', 'open_type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['adv_name'], 'string', 'max' => 100],
            [['remark'], 'string', 'max' => 500],
            [['adv_name'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'adv_type' => '位置',
            'adv_height' => '图片高',
            'adv_width' => '图片宽',
            'adv_name' => '名称',
            'show_type' => '展示方式',
            'open_type' => '新窗口',
            'remark' => '广告位描述',
            'status' => '是否启用',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * 语言扩展表
     * @return \common\models\goods\AttributeLang
     */
    public function langModel()
    {
        return new AdvertLang();
    }

    public function getLangs()
    {
        return $this->hasMany(AdvertLang::class,['master_id'=>'id']);

    }

    /**
     * 关联语言一对一
     * @param string $languge
     * @return \yii\db\ActiveQuery
     */
    public function getLang()
    {
        $query = $this->hasOne(AdvertLang::class, ['master_id'=>'id'])->alias('lang')->where(['lang.language' => Yii::$app->language]);
        return $query;
    }

}
