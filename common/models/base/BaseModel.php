<?php

namespace common\models\base;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Class BaseModel
 * @package common\models\common
 * @author jianyan74 <751393839@qq.com>
 */
class BaseModel extends ActiveRecord
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }
    
    /**
     *
     * @param unknown $attribute
     * @param unknown $params
     */
    public function implodeArray($attribute, $params)
    {
        $split = isset($params['split'])?$params['split']:',';
        if(is_array($this->$attribute) && !empty($this->$attribute)){
            $this->$attribute = implode($split, $this->$attribute);
        }
    }
}