<?php

namespace common\components;


use Yii;
use yii\base\Component;
use wsl\ip2location\Ip2Location;
use wsl\ip2location\QQWry;
use common\enums\AreaEnum;


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
        if($ip == null) {
            $ip = \Yii::$app->request->userIP;
        }
        $area_id = 0;
        $country = null;
        $area = null;
        $address = null;
        $location = $this->handle->getLocation($ip);
        if($location) {
            if(preg_match("/香港/is",$location->country)){
                $area_id = AreaEnum::HongKong;
            }
            elseif(preg_match("/澳门/is",$location->country)){
                $area_id = AreaEnum::MaCao;
            }
            elseif(preg_match("/台湾/is",$location->country)){
                $area_id = AreaEnum::TaiWan;
            }
            elseif(preg_match("/省|中国/is",$location->country)) {
                $area_id = AreaEnum::China;
            }
            else {
                $area_id = AreaEnum::Other;
            }
            $address = $country.' '.$area;
        }
        return [$area_id,$address,$country,$area];
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
    /**
     * 更新IP库
     */
    public function updateQQWry()
    {
        (new QQWry())->upgrade();
    }
    
    
    
}