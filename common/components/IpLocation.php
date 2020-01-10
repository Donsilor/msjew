<?php

namespace common\components;


use Yii;
use yii\base\Component;
use wsl\ip2location\Ip2Location;


/**
 * 支付组件
 *
 * Class Pay
 * @package common\components
 * @property \common\components\payment\WechatPay $wechat
 * @property \common\components\payment\AliPay $alipay
 * @property \common\components\payment\UnionPay $union
 * @author jianyan74 <751393839@qq.com>
 */
class IpLocation extends Component {
    
    public $handle;
    
    public function init() {
        if(!$this->handle) {
            $this->handle = new Ip2Location(); 
        }
        parent::init();
    }
    /**
     * 获取地区信息
     * @param unknown $ip
     */
    public function getLocation($ip = null)
    {
        $this->handle->getLocation($ip);
    }   
    
}