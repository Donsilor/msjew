<?php

namespace common\models\order;

use Yii;

/**
 * This is the model class for table "{{%order_tourist}}".
 *
 * @property int $id 主键
 * @property int $merchant_id 商户ID
 * @property int $store_id 店铺id
 * @property int $tourist_key 游客的KEY
 * @property string $order_amount 订单金额
 * @property string $currency 货币
 * @property double $exchange_rate 汇率
 * @property string $ip 下单时IP
 * @property int $status 状态：0未支付，1已支付，2已同步到标准订单
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class OrderTourist extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order_tourist}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'store_id', 'tourist_key', 'status', 'created_at', 'updated_at'], 'integer'],
            [['order_amount', 'exchange_rate'], 'number'],
            [['currency'], 'string', 'max' => 3],
            [['ip'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '主键'),
            'merchant_id' => Yii::t('app', '商户ID'),
            'store_id' => Yii::t('app', '店铺id'),
            'tourist_key' => Yii::t('app', '游客的KEY'),
            'order_amount' => Yii::t('app', '订单金额'),
            'currency' => Yii::t('app', '货币'),
            'exchange_rate' => Yii::t('app', '汇率'),
            'ip' => Yii::t('app', '下单时IP'),
            'status' => Yii::t('app', '状态：0未支付，1已支付，2已同步到标准订单'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
        ];
    }
    
    /**
     * 对应多个商品
     * @return \yii\db\ActiveQuery
     */
    public function getDetails()
    {
        return $this->hasMany(OrderTouristDetails::class,['order_tourist_id'=>'id']);
    }

}
