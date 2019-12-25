<?php

namespace api\modules\web\forms;


use yii\base\Model;

/**
 * Class LoginForm
 * @package api\modules\v1\forms
 */
class CartForm extends Model
{
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
                [['style_id', 'goods_id', 'buyer_id'], 'required'],
        ];
    }
    
    public function attributeLabels()
    {
        return [
                'title' => '主题',
                'content' => '内容',
                'member_id' => '用户ID',
                
        ];
    }
    
    
}
