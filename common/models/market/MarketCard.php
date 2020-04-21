<?php

namespace common\models\market;

use common\enums\CardDetailStatusEnum;
use common\enums\CardTypeEnum;
use Yii;

/**
 * This is the model class for table "market_card".
 *
 * @property int $id
 * @property string $batch 批次
 * @property string $sn 卡号
 * @property string $password 卡密
 * @property string $balance 可用余额
 * @property string $amount 金额
 * @property int $start_time 开始时间
 * @property int $end_time 结束时间
 * @property int $status 状态：1=启用，0=禁用
 * @property int $user_id 用户ID
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class MarketCard extends \common\models\base\BaseModel
{
    public $count;

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
            [['start_time', 'end_time', 'goods_type_attach','batch','user_id'], 'required'],
            [['balance', 'amount'], 'number'],
            [['status', 'created_at', 'updated_at','user_id'], 'integer'],
            [['sn', 'password'], 'string', 'max' => 255],
            [['batch'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'batch' => '批次',
            'sn' => '卡号',
            'password' => '卡密',
            'balance' => '可用余额',
            'amount' => '金额',
            'goods_type_attach' => '产品线',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'status' => '状态',
            'user_id' => '操作人',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public function beforeSave($insert)
    {
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    /**
     * 验证密码
     * @param $password
     * @return bool
     */
    public function validatePassword($pw)
    {
        return $this->getPassword() == $pw;
    }

    public function setPassword($password)
    {
        $key = (\Yii::$app->params['card-key']??'card-default-key'). $this->sn;;

        $this->password = base64_encode(Yii::$app->getSecurity()->encryptByPassword($password, $key));
    }

    public function getPassword()
    {
        $key = (\Yii::$app->params['card-key']??'card-default-key'). $this->sn;

        return Yii::$app->getSecurity()->decryptByPassword(base64_decode($this->password), $key);
    }

    /**
     * 对应订单购物卡记录
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\common\models\backend\Member::class, ['id'=>'user_id']);
    }

    /**
     * 产品线
     * @return \yii\db\ActiveQuery
     */
    public function getGoodsType()
    {
        return $this->hasMany(MarketCardGoodsType::class, ['batch'=>'batch']);
    }

    public function getFrozenAmount($field='use_amount')
    {
        static $amount = [];
        $key = $this->id . $field;
        if(!isset($amount[$key])) {
            $amount[$key] = abs(MarketCardDetails::find()->where(['card_id'=>$this->id, 'type'=>CardTypeEnum::CONSUME, 'status'=>CardDetailStatusEnum::FROZEN])->sum($field));
        }
        return $amount[$key];
    }

}
