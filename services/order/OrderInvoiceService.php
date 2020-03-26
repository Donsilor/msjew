<?php

namespace services\order;

use common\components\Service;
use common\enums\CurrencyEnum;
use common\helpers\ResultHelper;
use common\models\order\OrderCart;
use common\models\order\OrderGoodsLang;
use common\models\order\OrderInvoice;
use common\models\order\OrderInvoiceEle;
use yii\web\UnprocessableEntityHttpException;
use common\models\order\OrderGoods;
use common\models\order\Order;
use common\models\member\Address;
use common\models\order\OrderAccount;
use common\models\order\OrderAddress;
use common\enums\PayStatusEnum;
use common\models\common\EmailLog;
use common\models\member\Member;
use common\helpers\RegularHelper;
use common\models\common\SmsLog;
use common\enums\OrderStatusEnum;
use common\enums\ExpressEnum;
use common\enums\StatusEnum;
use common\models\order\OrderLog;

/**
 * Class OrderService
 * @package services\order
 */
class OrderInvoiceService extends OrderBaseService
{
    public function getEleInvoiceInfo($order_id){
        $order = Order::find()
            ->where(['id'=>$order_id])
            ->one();
        if(empty($order)) {
            throw new UnprocessableEntityHttpException("订单不存在");
        }
        $language = $order->language;
        $result = array(
            'invoice_date' => $order->delivery_time,
            'sender_name' => '',
            'sender_address'=> '',
            'shipper_name' => '',
            'shipper_address' => '',
            'realname' => $order->address->realname,
            'address_details' => $order->address->address_details,
            'express_no' => $order->express_no,
            'express_company_name' => $order->express_id ? $order->express->lang->express_name:'',
            'delivery_time' => $order->delivery_time,
            'country' => $order->address->country_name,
            'currency' => CurrencyEnum::getValue($order->account->currency),
            'order_amount' => $order->account->order_amount
        );


        $order_invoice_exe = OrderInvoiceEle::find()
            ->where(['order_id'=>$order_id])
            ->asArray()
            ->one();
        if($order_invoice_exe){
            $result['invoice_date'] = $order_invoice_exe['invoice_date'] ? $order_invoice_exe['invoice_date'] : $result['invoice_date'];
            $result['sender_name'] = $order_invoice_exe['sender_name'] ? $order_invoice_exe['sender_name'] : $result['sender_name'];
            $result['sender_address'] = $order_invoice_exe['sender_address'] ? $order_invoice_exe['sender_address'] : $result['sender_address'];
            $result['shipper_name'] = $order_invoice_exe['shipper_name'] ? $order_invoice_exe['shipper_name'] : $result['shipper_name'];
            $result['shipper_address'] = $order_invoice_exe['shipper_address'] ? $order_invoice_exe['shipper_address'] : $result['shipper_address'];
            $result['express_company_name'] = $order_invoice_exe['express_company_name'] ? $order_invoice_exe['express_company_name'] : $result['express_company_name'];
            $result['express_no'] = $order_invoice_exe['express_no'] ? $order_invoice_exe['express_no'] : $result['express_no'];
            $result['delivery_time'] = $order_invoice_exe['delivery_time'] ? $order_invoice_exe['delivery_time'] : $result['delivery_time'];
            $result['delivery_time'] = $order_invoice_exe['delivery_time'] ? $order_invoice_exe['delivery_time'] : $result['delivery_time'];
            $language = $order_invoice_exe['language'] ? $order_invoice_exe['language'] : $language;
        }


        //商品明细
        $order_goods = OrderGoods::find()->alias('m')
            ->leftJoin(OrderGoodsLang::tableName().'lang','m.id=lang.master_id and lang.language="'.$language.'"')
            ->where(['order_id'=>$order_id])
            ->select(['lang.goods_name','m.goods_num','m.goods_pay_price','m.currency'])
            ->asArray()
            ->all();
        $result['order_goods'] = $order_goods;



        //不同语言差异代码
        if($language == 'en-US'){
            $result['invoice_date'] = $result['invoice_date'] ? date('d-m-Y',$result['invoice_date']):date('d-m-Y',time());
            $result['delivery_time'] = $result['delivery_time'] ? date('d-m-Y',$result['delivery_time']):'';

            $city_name = $order->address->city_name ? ','.$order->address->city_name : '';
            $province_name = $order->address->province_name ? ','.$order->address->province_name : '';
            $country_name = $order->address->country_name ? ','.$order->address->country_name : '';
            $result['address_details'] = $order->address->address_details .$city_name.$province_name.$country_name ;
        }else{
            $result['invoice_date'] = $result['invoice_date'] ? date('Y-m-d',$result['invoice_date']):date('Y-m-d',time());
            $result['delivery_time'] = $result['delivery_time'] ? date('Y-m-d',$result['delivery_time']):'';

            $city_name = $order->address->city_name ? $order->address->city_name . ' ' : '';
            $province_name = $order->address->province_name ? $order->address->province_name . ' ' : '';
            $country_name = $order->address->country_name ? $order->address->country_name .' ' : '';
            $result['address_details'] = $country_name . $province_name . $city_name . $order->address->address_details ;
        }

        switch ($language){
            case 'en-US':
                $result['template'] = 'ele-invoice-us.php';
                break;
            case 'zh-CN':
                $result['template'] = 'ele-invoice-zh.php';
                break;
            default: $result['template'] = 'ele-invoice.php';

        }
        return $result;
    }
    
}