<?php

namespace common\models\member;

use Yii;

/**
 * This is the model class for table "{{%member_contact}}".
 *
 * @property int $id
 * @property string $language 语言类型
 * @property int $member_id 会员ID
 * @property string $first_name 名
 * @property string $last_name 姓
 * @property string $telphone 电话
 * @property int $type_id 留言类别
 * @property string $content 留言内容
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 */
class Contact extends \yii\db\ActiveRecord
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
            [['first_name', 'last_name', 'telphone', 'type_id'], 'required'],
            [['content'], 'string'],
            [['language'], 'string', 'max' => 5],
            [['first_name', 'last_name'], 'string', 'max' => 30],
            [['telphone'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('member_contact', 'ID'),
            'language' => '语言类型',
            'member_id' => '会员ID',
            'first_name' => '名',
            'last_name' => '姓',
            'telphone' => '电话',
            'type_id' => '留言类别',
            'content' => '留言内容',
            'status' => Yii::t('member_contact', 'Status'),
            'created_at' => Yii::t('member_contact', 'Created At'),
            'updated_at' => Yii::t('member_contact', 'Updated At'),
        ];
    }
}
