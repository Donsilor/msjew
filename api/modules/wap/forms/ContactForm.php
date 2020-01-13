<?php

namespace api\modules\wap\forms;

use yii\base\Model;

/**
 * Class LoginForm
 * @package api\modules\v1\forms
 * @author jianyan74 <751393839@qq.com>
 */
class ContactForm extends Model
{
    public $first_name;
    public $last_name;
    public $telphone;
    public $mobile_code;
    public $book_time;
    public $book_date;
    public $type_id;
    public $member_id;
    public $language;
    public $content;
    public $ip;
    public $city;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'telphone','mobile_code','book_time','book_date','type_id'], 'required'],
            [['first_name','last_name'], 'string', 'max' => 30],
            //['telphone', 'match', 'pattern' => RegularHelper::mobile(), 'message' => '请输入正确的手机号'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'first_name' => '姓',
            'last_name' => '名',
            'telphone' => '电话',
            'mobile_code' => '区号',
            'book_time' => '预约时间',
            'book_date' => '预约日期',
            'type_id' => '产品需求',

        ];
    }


}
