<?php

namespace services\market;

use common\components\Service;

/**
 * Class CardService
 * @package services\market
 */
class CardService extends Service
{
    //调整金额

    //消费金额
    static public function consume($order, $cards)
    {

    }

    static public function getUseAmount($order_id)
    {
        return 0;
    }

    //生成卡密码
    public function generatePw($prefix = '')
    {
        return $prefix.str_pad(mt_rand(1, 99999999999),11,'0',STR_PAD_LEFT);
    }

    //生成卡号
    public function generateSn($prefix = 'BDD')
    {
        return $prefix.date('Y').str_pad(mt_rand(1, 999999),6,'0',STR_PAD_LEFT);
    }

    //批量生成购物卡

    /**
     * 生成购物卡
     * @param array $card 基本数据
     * @param int $count
     * @param string $batch
     */
    public function generateCard($card, $count, $batch)
    {

    }

    //导出|导入数据
}