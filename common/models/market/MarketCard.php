<?php

namespace common\models\market;

use Yii;

/**
 * This is the model class for table "market_card".
 *
 * @property int $id
 * @property string $sn 卡号
 * @property string $password 卡密
 * @property string $balance 可用余额
 * @property string $amount 金额
 * @property int $start_time 开始时间
 * @property int $end_time 结束时间
 * @property int $status 状态：1=启用，0=禁用
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class MarketCard extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'market_card';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['balance', 'amount'], 'number'],
            [['start_time', 'end_time', 'status', 'created_at', 'updated_at'], 'integer'],
            [['sn', 'password'], 'string', 'max' => 80],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sn' => '卡号',
            'password' => '卡密',
            'balance' => '可用余额',
            'amount' => '金额',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'status' => '状态：1=启用，0=禁用',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}
