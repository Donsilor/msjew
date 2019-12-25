<?php

namespace api\modules\web\forms;


use yii\base\Model;

/**
 * Class LoginForm
 * @package api\modules\v1\forms
 */
class CartForm extends Model
{
    
    public $goods_id;//商品ID
    public $goods_type;//商品类型(产品线ID)
    public $goods_num;//商品数量
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
             [['goods_id','goods_type','goods_num'], 'required'],
             [['goods_id','goods_type','goods_num'], 'number'],
        ];
    }
    
    public function attributeLabels()
    {
        return [
                'goods_id' => '商品ID',
                'goods_type' => '商品类型',
                'goods_num' => '商品数量',                
        ];
    }
    
    
}
