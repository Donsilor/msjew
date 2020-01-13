<?php

namespace common\components;


use Yii;
use yii\base\Component;
use common\enums\AreaEnum;
use Zhuzhichao\IpLocationZh\Ip;


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
            $this->handle = new Ip(); 
        }
        parent::init();
    }
    /**
     * 获取地区信息
     * @param unknown $ip
     */
    public function getLocation($ip = null)
    {
        if($ip == null) {
            $ip = \Yii::$app->request->userIP;
        }
        $area_id = 0;
        $country = null;
        $province = null;
        $city = null;
        $area = null;
        $address = null;
        $locations = $this->handle->find($ip);
        if(!empty($locations) && is_array($locations)) {
            list($country,$province,$city,$area,$code) = $locations;
            if($country == '中国') { 
                $area_id = AreaEnum::China;
                if($province == "香港") {
                    $area_id = AreaEnum::HongKong;
                }elseif($province == "澳门"){
                    $area_id = AreaEnum::MaCao;
                }elseif($province == "台湾"){
                    $area_id = AreaEnum::TaiWan;
                }
            }else {
                $area_id = AreaEnum::Other;
            }
            $address = $country.' '.$province.' '.$city.' '.$area;
        }
        return [$area_id,trim($address),$country,$province,$city,$area];
    }    
    /**
     * 获取IP区域
     * @param unknown $ip
     */
    public function getAreaId($ip = null)
    {
        $location = $this->getLocation($ip);                
        return $location[0];
    }      
    
    
}