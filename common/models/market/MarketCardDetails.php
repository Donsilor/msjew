<?php

namespace common\models\market;

use Yii;

/**
 * This is the model class for table "market_card_details".
 *
 * @property int $id ID
 * @property int $card_id 购物卡ID
 * @property string $use_amount 使用金额
 * @property string $balance 使用后余额
 * @property string $ip IP
 * @property int $user_id 管理员ID
 * @property int $member_id 客户ID
 * @property int $type 消费类型：1=余额调整，2=客户购物消费，3=客户购物退款
 * @property int $status 状态 1有效 0无效
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class MarketCardDetails extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'market_card_details';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['card_id', 'use_amount', 'balance', 'ip', 'created_at', 'updated_at'], 'required'],
            [['card_id', 'user_id', 'member_id', 'type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['use_amount', 'balance'], 'number'],
            [['ip'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'card_id' => '购物卡ID',
            'use_amount' => '使用金额',
            'balance' => '使用后余额',
            'ip' => 'IP',
            'user_id' => '管理员ID',
            'member_id' => '客户ID',
            'type' => '消费类型：1=余额调整，2=客户购物消费，3=客户购物退款',
            'status' => '状态 1有效 0无效',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}
