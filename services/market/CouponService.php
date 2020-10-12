<?php

namespace services\market;

use common\components\Service;
use common\enums\AreaEnum;
use common\enums\CouponStatusEnum;
use common\enums\OrderStatusEnum;
use common\enums\OrderTouristStatusEnum;
use common\enums\PayStatusEnum;
use common\enums\PreferentialTypeEnum;
use common\models\api\AccessToken;
use common\models\goods\Goods;
use common\models\goods\Style;
use common\models\market\MarketCoupon;
use common\models\market\MarketCouponArea;
use common\models\market\MarketCouponDetails;
use common\models\market\MarketCouponGoods;
use common\models\market\MarketCouponGoodsType;
use common\models\member\Member;
use common\models\order\Order;
use common\models\order\OrderAccount;
use common\models\order\OrderAddress;
use common\models\order\OrderCart;
use common\models\order\OrderGoods;
use common\models\order\OrderInvoice;
use common\models\order\OrderTourist;
use common\models\order\OrderTouristDetails;
use common\models\order\OrderTouristInvoice;
use PayPal\Api\PayerInfo;
use PayPal\Api\Payment;
use PayPal\Api\ShippingAddress;
use services\goods\TypeService;
use yii\base\Exception;
use yii\db\Expression;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class CouponService
 * @package services\market
 */
class CouponService extends Service
{
    /**
     * 增加折扣券的使用
     * @param int $coupon_id
     * @param int $type_id
     * @param int $style_id
     * @param int $num
     * @return bool
     */
    static public function incrDiscountUse($coupon_id, $type_id, $style_id, $num)
    {
        try {
            $result = true;

            $where = [];
            $where['coupon_id'] = $coupon_id;
            $where['goods_type'] = $type_id;
            $where['style_id'] = $style_id;

            $goods = MarketCouponGoods::findOne($where);

            if(!$goods) {
                $coupon = MarketCoupon::findOne($coupon_id);

                if(!$coupon || $coupon->type!=PreferentialTypeEnum::DISCOUNT) {
                    throw new \Exception('error');
                }

                if(!in_array($type_id, $coupon->goods_type_attach)) {
                    throw new \Exception('error');
                }

                $goods = new MarketCouponGoods();
                $goods->setAttributes($where);
                $goods->specials_id = $coupon->specials_id;
                $goods->count = $coupon->count;

                $goods->get_count = $num;
                $goods->save();
            }
            else {
                $data = [
                    'get_count'=> new Expression("get_count+({$num})")
                ];
                $where = ['and'];
                $where[] = [
                    'id' => $goods->id
                ];
                $where[] = [
                    '<', 'get_count', new Expression("count")
                ];
                $result = MarketCouponGoods::updateAll($data, $where) > 0;
            }
        } catch (\Exception $exception) {
            $result = false;
        }

        return $result;
    }

    /**
     * 增加优惠券的使用
     * @param int $coupon_id
     * @param int $num
     * @return bool
     */
    static public function incrMoneyUse($coupon_id, $num)
    {
        $data = [
            'get_count'=> new Expression("get_count+({$num})")
        ];

        $where = ['and'];
        $where[] = [
            'id' => $coupon_id,
            'type' => PreferentialTypeEnum::MONEY
        ];
        $where[] = [
            '<', 'get_count', new Expression("count")
        ];

        return MarketCoupon::updateAll($data, $where) > 0;
    }

    /**
     * @param int $coupon_id 活动ID
     * @param int $member_id 会员ID
     * @return MarketCouponDetails
     * @throws UnprocessableEntityHttpException
     */
    static public function fetchCoupon($coupon_id, $member_id)
    {
        $coupon = MarketCoupon::findOne($coupon_id);

        if(!$coupon) {
            throw new UnprocessableEntityHttpException("优惠券已失效");
        }

        //活动状态判断
        if(!$coupon->status || !$coupon->specials->status) {
            throw new UnprocessableEntityHttpException("券不能被领取");
        }

        //活动时间判断
        if($coupon->specials->end_time<time()) {
            throw new UnprocessableEntityHttpException("活动已结束");
        }

        //折扣券剩余判断
        if($coupon->count<=$coupon->get_count) {
            throw new UnprocessableEntityHttpException("券已被领完了");
        }

        //券的使用数加1
        CouponService::incrMoneyUse($coupon->id, 1);

        $couponDetails = new MarketCouponDetails();
        $couponDetails->setAttributes([
            //'merchant_id' => $coupon->specials_id,
            'specials_id' => $coupon->specials_id,
            'coupon_id' => $coupon->id,
            'coupon_code' => self::generatedCouponSn(),
            'coupon_status' => CouponStatusEnum::COUPON_FETCH,
            'member_id' => $member_id,
            'get_type' => 2,
            'fetch_time' => time(),
        ]);

        $couponDetails->save();
        if(!$couponDetails->save()) {
            throw new UnprocessableEntityHttpException("券保存失败");
        }

        return $couponDetails;
    }

    /**
     * @param string $prefix
     * @return string
     */
    static public function generatedCouponSn($prefix = 'CP')
    {
        return $prefix.date('Ymd').mt_rand(3,9).str_pad(mt_rand(1, 99999),6,'1',STR_PAD_LEFT);
    }

    /**
     * 生成优惠数据
     * @param MarketCoupon $model
     * @throws Exception
     */
    static public function generatedData(MarketCoupon $model)
    {
        $where = [
            'specials_id' => $model->specials_id,
            'coupon_id' => $model->id
        ];

        //验证并生成产品数据
        $ids = [];
        foreach ($model->goods_attach as $goodsSn) {

            $goodsData = Style::find()->where(['style_sn'=>$goodsSn])->select(['id', 'type_id'])->one();

            if(empty($goodsData)) {
                throw new Exception(sprintf('[%s]产品未找到~！', $goodsSn));
            }

            $data = [
                'goods_type' => $goodsData->type_id,
                'style_id' => $goodsData->id
            ];

            if(($goods = MarketCouponGoods::find()->where(array_merge($where, $data))->one())) {

                //如果商品存在，则维护商品数据
                if($goods->exclude) {
                    $goods->exclude = 0;
                    $goods->save();
                }

                $ids[] = $goods->id;
                continue;
            }
            else {

                $goods = new MarketCouponGoods();
                $goods->setAttributes($where);
                $goods->setAttributes($data);
                $goods->count = $model->count;

                if(!$goods->save()) {
                    throw new Exception(sprintf('[%d]产品保存失败~！', $goodsSn));
                }
                $ids[] = $goods->id;
            }
        }

        //商品排除
        MarketCouponGoods::updateAll(['exclude'=>1], array_merge(['and'], [$where], [['NOT IN', 'id', $ids]]));

        //验证并生成产品线数据
        $typeList = TypeService::getTypeList();
        foreach ($model->goods_type_attach as $goodsTypeId) {
            if(!isset($typeList[$goodsTypeId])) {
                throw new Exception(sprintf('[%d]产品线未找到~！', $goodsTypeId));
            }

            if(MarketCouponGoodsType::find()->where(array_merge($where, ['goods_type'=>$goodsTypeId]))->count('id')) {
                continue;
            }

            $goodsType = new MarketCouponGoodsType();
            $goodsType->setAttributes($where);
            $goodsType->setAttributes(['goods_type'=>$goodsTypeId]);
            if(!$goodsType->save()) {
                throw new Exception(sprintf('[%d]产品线保存失败~！', $goodsTypeId));
            }
        }

        //产品线排除
        MarketCouponGoodsType::deleteAll(array_merge(['and'], [$where], [['NOT IN', 'goods_type', $model->goods_type_attach]]));

        //启用产品线对应的商品
        MarketCouponGoods::updateAll(['exclude'=>0], array_merge(['and'], [$where], [['IN', 'goods_type', $model->goods_type_attach]]));

        //生成地区数据
        foreach ($model->area_attach as $areaId) {
            if(empty(AreaEnum::getValue($areaId))) {
                throw new Exception(sprintf('[%d]地区未找到~！', $areaId));
            }

            if(MarketCouponArea::find()->where(array_merge($where, ['area_id'=>$areaId]))->count('id')) {
                continue;
            }

            $area = new MarketCouponArea();
            $area->setAttributes($where);
            $area->setAttributes(['area_id'=>$areaId]);
            if(!$area->save()) {
                throw new Exception(sprintf('[%d]地区保存失败~！', $areaId));
            }
        }

        //地区排除
        MarketCouponArea::deleteAll(array_merge(['and'], [$where], [['NOT IN', 'area_id', $model->area_attach]]));
    }

    //所有进行中优惠信息列表
    static public function getCoupons($areaId, $type=null, $timeStatus=null)
    {
        static $data = [];

        $key = $areaId.'-'.$type.'-'.$timeStatus;
        if(isset($data[$key])) {
            return $data[$key];
        }

        $where = [
            'and'
        ];

        //券的类型
        if(!empty($type)) {
            $where[] = [
                'market_coupon.type' => $type
            ];
        }

        $where[] = [
            'market_coupon_area.area_id' => $areaId,
            'market_coupon.status' => 1,
            'market_specials.status' => 1,
        ];

        $time = time();
        if($timeStatus==1) {
            //未开始
            $where[] = ['>', 'market_specials.start_time', $time];
        } elseif($timeStatus==2) {
            //时行中
            $where[] = ['<=', 'market_specials.start_time', $time];
            $where[] = ['>=', 'market_specials.end_time', $time];
        } elseif($timeStatus==3) {
            //已结束
            $where[] = ['<', 'market_specials.end_time', $time];
        }

        $data[$key] = MarketCoupon::find()
            ->leftJoin('market_specials', 'market_coupon.specials_id=market_specials.id')
            ->leftJoin('market_coupon_area', 'market_coupon.id=market_coupon_area.coupon_id')
            ->where($where)
            ->all();

        return $data[$key];
    }

    //获取产品线优惠信息
//    static public function getTypeCouponByTypes($areaId, $goodsTypes)
//    {
//        //优惠券列表
//        $couponTypes = [];
//
//        //所有优惠ID
//        $couponIds = [];
//
//        //产品线活动信息
//        $couponForGoodsType = [];
//
//        $coupons = self::getCoupons($areaId, null, 2);
//        foreach ($coupons as $coupon) {
//            $couponTypes[$coupon->type][$coupon->id] = $coupon;
//            $couponIds[] = $coupon->id;
//        }
//
//        //获取产品线
//        $where = [];
//        $where['coupon_id'] = $couponIds;
//        $where['goods_type'] = $goodsTypes;
//        $couponGoodsTypes = MarketCouponGoodsType::find()->where($where)->all();
//
//        foreach ($couponGoodsTypes as $couponGoodsType) {
//            foreach (PreferentialTypeEnum::getMap() as $key => $value) {
//                if(!empty($couponTypes[$key]) && !empty($couponTypes[$key][$couponGoodsType->coupon_id])) {
//                    $couponForGoodsType[$couponGoodsType->goods_type][$key][] = $couponTypes[$key][$couponGoodsType->coupon_id];
//                }
//            }
//        }
//
//        return $couponForGoodsType;
//    }


    /**
     * 列表，根据活动类型，地区，产品线，款式,价格获取优惠信息
     * @param int $areaId 区域ID
     * @param array $records 商品列表数据
     * @param bool $validateCouponCount 是否验证优惠券数量
     * @return void|array
     * @throws UnprocessableEntityHttpException
     */
    static public function getCouponByList($areaId, &$records, $validateCouponCount = true)
    {
//        $coupon = [
//            'type_id',//产品线ID
//            'style_id',//款式ID
//            'price',//价格
//            'num',//币种
//        ];
        if(empty($records) || !is_array($records)) {
            return;
        }

        //产品线ID
        $goodsTypeIds = [];

        //款式列表
        $styles = ['or'];

        foreach ($records as $record) {
            if(empty($record['coupon'])) {
                continue;
            }
            $style = $record['coupon'];
            $goodsTypeIds[] = $style['type_id'];
            $styles[] = [
                'goods_type' => $style['type_id'],
                'style_id' => $style['style_id'],
            ];
        }

        if(empty($goodsTypeIds)) {
            return;
        }

        //优惠券列表
        $couponTypes = [];
        $couponList = [];

        //所有优惠ID
        $couponIds = [];

        $coupons = self::getCoupons($areaId, null, 2);
        foreach ($coupons as $coupon) {
            $couponList[$coupon->id] = $coupon;
            $couponTypes[$coupon->type][$coupon->id] = $coupon;
            $couponIds[] = $coupon->id;
        }

        //获取产品线
        $where = [];
        $where['coupon_id'] = $couponIds;
        $where['goods_type'] = $goodsTypeIds;
        $couponGoodsTypes = MarketCouponGoodsType::find()->where($where)->all();

        //产品活动信息
        $couponForGoods = [];

        foreach ($couponGoodsTypes as $couponGoodsType) {
            $coupun = $couponList[$couponGoodsType->coupon_id];
            $couponForGoods[$couponGoodsType->goods_type][$coupun->type][$coupun->id] = $coupun;
        }

        //获取款式列表
        $couponGoods = MarketCouponGoods::find()->where(['coupon_id'=>$couponIds,'exclude'=>0])->andWhere($styles)->all();

        //款式活动信息
        foreach ($couponGoods as $goods) {
            $coupun = $couponList[$goods->coupon_id];

            $goodsCouponKey = $goods->goods_type . '-' . $goods->style_id;
            $goodsKey = $goods->goods_type . '-' . $goods->style_id . '-goods';

            //款式对应的活动
            $couponForGoods[$goodsCouponKey][$coupun->type][$coupun->id] = $coupun;

            //商品的活动信息
            $couponForGoods[$goodsKey][$coupun->id] = $goods;
        }

        //把列表单成一个订单，可以使用的优惠券
        $couponsList = [];

        /**
         * 1、有折扣则不能使用优惠券
         * 2、优惠券最低使用金额过滤
         * 3、过滤可使用数不足的券
         */
        foreach ($records as &$record) {
            $style = $record['coupon'];

            $goodsCouponKey = $style['type_id'] . '-' . $style['style_id'];
            $goodsKey = $style['type_id'] . '-' . $style['style_id'] . '-goods';

            $goodsInfos = $couponForGoods[$goodsKey]??[];//商品信息
            $goodsCoupon = $couponForGoods[$goodsCouponKey]??[];//商品活动
            $goodsTypeCoupon = $couponForGoods[$style['type_id']]??[];//产品线活动列表

            //合并款式和产品线折扣活动列表
            $discounts = array_merge($goodsCoupon[PreferentialTypeEnum::DISCOUNT]??[], $goodsTypeCoupon[PreferentialTypeEnum::DISCOUNT]??[]);

            //获取最优折扣
            $coupon = self::getDiscount($discounts, $goodsInfos);

            //如果没有折扣，则获到优惠券列表
            if(!empty($coupon)) {
                //折扣价格计算
                $coupon['price'] = round($style['price']*$coupon['discount']/100, 2);
                $record['coupon']['discount'] = $coupon;
                continue;
            }

            $coupons = [];//款式的优惠券列表

            //合并优惠券
            $moneys = array_merge($goodsCoupon[PreferentialTypeEnum::MONEY]??[], $goodsTypeCoupon[PreferentialTypeEnum::MONEY]??[]);
            foreach ($moneys as $money) {
//                过滤金额不可用的券（最低使用金额不为0且小于款式金额，则过滤）
//                if($money->at_least!=0 && $money->at_least > $style['price']) {
//                    continue;
//                }

                //过滤可用数量不足的券
                if($money->count <= $money->get_count) {
                    //验证我是否已领取此券,只能已登陆的时候用
                    if(!$validateCouponCount && (\Yii::$app->getUser()->identity instanceof AccessToken)) {
                        $where = [];
                        $where['coupon_id'] = $money->id;
                        $where['coupon_status'] = 1;
                        $where['member_id'] = \Yii::$app->getUser()->identity->member->id;
                        if (!MarketCouponDetails::find()->where($where)->count('id')) {
                            continue;
                        }
                    }
                    else {
                        continue;
                    }
                }

                /**
                 * @var number $price 转换后的价格
                 */
//                $price = \Yii::$app->services->currency->exchangeAmount($money->money);

                $coupon = [
                    'coupon_id' => $money->id,
                    'specials_id' => $money->specials_id,
                    'count' => $money->count,
                    'get_count' => $money->get_count,
                    'money' => self::_exchangeAmount($money->money),
                    'money_cn' => $money->money,
                    'at_least' => self::_exchangeAmount($money->at_least),
                    'at_least_cn' => $money->at_least,
                    'start_time' => $money->specials->start_time,
                    'end_time' => $money->specials->end_time,
                    'goods_type' => $money->getGoodsType(),
                    'is_goods' => !empty($money->goods_attach),
//                    'price' => $style['price']-$price,//这里需要汇率转换
                ];

                $coupons[] = $coupon;

                if(!isset($couponsList[$money->id])) {
                    //累加金额
                    $coupon['price'] = $style['price'];

                    $couponsList[$money->id] = $coupon;
                }
                else {
                    //累加金额
                    $couponsList[$money->id]['price'] = bcadd($couponsList[$money->id]['price'], $style['price'], 2);
                }
            }

            if(!empty($coupons)) {
                //排序
                $coupons = self::arraySort($coupons, 'money');
                $moneys = [];
                foreach ($coupons as $coupon) {
                    $moneys[$coupon['coupon_id']] = $coupon;
                }

                $record['coupon']['money'] = $moneys;
            }
        }

        //排序
        $couponsList = self::arraySort($couponsList, 'money');

        $result = [];
        foreach ($couponsList as $key => $item) {
            //过滤不能使用的券
            if($item['at_least']==0 || $item['at_least'] <= $item['price']) {
                $result[$item['coupon_id']] = $item;
            }
        }

        //返回可以使用券的列表
        return $result;
    }

    /**
     *
     * @param \common\components\unknown $money
     * @return array
     * @throws UnprocessableEntityHttpException
     */
    static public function _exchangeAmount($money)
    {
        return \Yii::$app->services->currency->exchangeAmount($money);
    }

    /**
     * 获取最优折扣券券
     * @param array $discounts
     * @param array $goodsInfos
     * @return null|array 返回折扣优惠信息
     */
    static public function getDiscount($discounts, $goodsInfos=[])
    {
        if(empty($discounts)) {
            return null;
        }

        //排序
        $discounts = self::arraySort($discounts, 'discount');

        //
        foreach ($discounts as $discount) {
            //折扣券使用数
            $getCount = 0;
            if(!empty($goodsInfos[$discount->id])) {
                $getCount = ($goodsInfos[$discount->id])->get_count;
            }

            //有可用折扣券，返回折扣信息
            if($getCount < $discount->count) {
                return [
                    'coupon_id' => $discount->id,
                    'specials_id' => $discount->specials_id,
                    'count' => $discount->count,
                    'get_count' => $getCount,
                    'discount' => $discount->discount,

                    'start_time' => $discount->specials->start_time,
                    'end_time' => $discount->specials->end_time,
                    'goods_type' => $discount->getGoodsType(),
                    'is_goods' => !empty($discount->goods_attach),
                ];
            }
        }

        return null;
    }

    /**
     * 排序方法
     * @param array $array
     * @param $keys
     * @param int $sort 排序方法：SORT_DESC=降序，SORT_ASC=升序
     * @return array
     */
    static private function arraySort($array, $keys, $sort = SORT_ASC) {
        $keysValue = [];
        foreach ($array as $k => $v) {
            $keysValue[$k] = $v[$keys];
        }
        array_multisort($keysValue, $sort, $array);
        return $array;
    }

    //根据活动地区，产品线，款式

    /**
     * 应用统一的入口，所以这里用上列表的数据获取方法
     * @param $areaId
     * @param $type_id
     * @param $style_id
     * @param $price
     * @return mixed
     * @throws UnprocessableEntityHttpException
     */
    static public function getCouponByStyleInfo($areaId, $type_id, $style_id, $price)
    {
        //组装成列表数组
        $records = [
            [
                'coupon' => [
                    'type_id' => $type_id,
                    'style_id' => $style_id,
                    'price' => $price,
                ]
            ]
        ];

        self::getCouponByList($areaId, $records);

        return $records[0]['coupon'];
    }



}