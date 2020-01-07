<?php

namespace common\models\member;

use common\models\base\BaseModel;
use Yii;

/**
 * This is the model class for table "{{%member_contact}}".
 *
 * @property int $id
 * @property string $language 语言类型
 * @property int $member_id 会员ID
 * @property string $first_name 名
 * @property string $last_name 姓
 * @property string $email
 * @property string $telphone 电话
 * @property string $mobile_code 手机区号
 * @property int $type_id 留言类别
 * @property string $ip
 * @property string $city
 * @property string $content 留言内容
 * @property int $status
 * @property string $book_time 预约时间
 * @property int $created_at
 * @property int $updated_at
 */
class Contact extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%member_contact}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['member_id', 'type_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['content'], 'string'],
            [['book_time'], 'safe'],
            [['language'], 'string', 'max' => 5],
            [['first_name', 'last_name', 'telphone'], 'string', 'max' => 30],
            [['email'], 'string', 'max' => 60],
            [['mobile_code'], 'string', 'max' => 10],
            [['ip', 'city'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('member_book', 'ID'),
            'language' => '语言类型',
            'member_id' => '会员ID',
            'first_name' => '名',
            'last_name' => '姓',
            'email' => Yii::t('member_book', '邮箱'),
            'telphone' => '电话',
            'mobile_code' => '手机区号',
            'type_id' => '留言类别',
            'ip' => Yii::t('member_book', 'Ip'),
            'city' => Yii::t('member_book', '所属城市'),
            'content' => '留言内容',
            'status' => Yii::t('member_book', '跟进状态'),
            'book_time' => '预约时间',
            'created_at' => Yii::t('member_book', '留言时间'),
            'updated_at' => Yii::t('member_book', 'Updated At'),
        ];
    }


}
