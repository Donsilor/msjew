<?php

namespace common\models\goods;

use Yii;

/**
 * This is the model class for table "{{%goods_ring}}".
 *
 * @property string $id 主键ID
 * @property string $ring_name 对戒名称
 * @property string $ring_sn 对戒编码
 * @property string $ring_image 对戒封面图片
 * @property string $qr_code 对戒二维码
 * @property int $ring_salenum 对戒销量
 * @property int $ring_style 对戒款式（1-金典系列，2-排镶系列）
 * @property string $sale_price 销售价格
 * @property int $status 是否启用（1-是，0-否, -1）
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class Ring extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_ring}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ring_salenum', 'ring_style', 'status', 'created_at', 'updated_at'], 'integer'],
            [['sale_price'], 'number'],
            [['ring_name', 'ring_sn'], 'string', 'max' => 100],
            [['ring_image'], 'string', 'max' => 500],

            [['qr_code'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键ID',
            'ring_name' => '对戒名称',
            'ring_sn' => '对戒编码',
            'ring_image' => '对戒封面图片',
            'qr_code' => '对戒二维码',
            'ring_salenum' => '对戒销量',
            'ring_style' => '对戒款式',
            'sale_price' => '销售价格',
            'status' => '是否上架',
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
        return new RingLang();
    }

    public function getLangs()
    {
        return $this->hasMany(RingLang::class,['master_id'=>'id']);

    }

    /**
     * 关联语言一对一
     * @param string $languge
     * @return \yii\db\ActiveQuery
     */
    public function getLang()
    {
        $query = $this->hasOne(RingLang::class, ['master_id'=>'id'])->alias('lang')->where(['lang.language' => Yii::$app->language]);
        return $query;
    }
}
