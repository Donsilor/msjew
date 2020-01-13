<?php

namespace api\modules\wap\forms;

use yii\base\Model;

/**
 * 创建订单
 * Class OrderCreateForm
 * @package api\modules\v1\forms
 */
class OrderCreateForm extends Model
{
    public $cart_ids;
    public $buyer_address_id;
    public $buyer_remark;
    public $order_amount;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
                [['cart_ids','buyer_address_id','order_amount'], 'required'],
                [['buyer_address_id'], 'integer'],
                [['order_amount'], 'number'],                
                [['buyer_remark'], 'string','max'=>500],
                [['cart_ids'], 'validateIds'],
        ];
    }
    
    public function attributeLabels()
    {
        return [
                'cart_ids' => 'cart_ids',
                'order_amount' => 'order_amount',
                'buyer_address_id' => 'buyer_address_id',
                'buyer_remark' => '订单备注',
        ];
    }
    /**
     * 校验购物车ID
     * @param unknown $attribute
     * @return boolean
     */
    public function validateIds($attribute)
    {
        $value = $this->$attribute;
        if(!is_array($value)) {
            $value = explode(",",$value);
        }
        foreach ($value as $id) {
            if(!is_numeric($id)) {
                $this->addError($attribute, $attribute.'校验失败');
                return false;
            }
        }    
        $this->$attribute = $value;
        return true;
        
    }
    
    
}
