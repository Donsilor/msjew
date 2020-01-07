<?php

namespace backend\modules\order\forms;
use common\enums\AuditStatusEnum;

/**
 * 发货表单
 * Class DeliveryForm
 * @package backend\forms
  */
class DeliveryForm extends \common\models\order\Order
{

    public function rules()
    {
        return [
                [['express_id', 'express_no'], 'required'], 
                [['order_status','updated_at'], 'integer'],
                [['express_no'], 'validateOrderStatus'],
        ];
    }    
    /**
     * 订单状态校验
     * @param unknown $attribute
     * @return boolean
     */
    public function validateOrderStatus($attribute)
    {
        if($this->status != AuditStatusEnum::PASS) {
            $this->addError($attribute,"订单还未审核，不能发货");
            return false;
        }
    }
    
}