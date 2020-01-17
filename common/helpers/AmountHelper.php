<?php


namespace common\helpers;


class AmountHelper
{
    /**
     * 输出金额
     * @param float $amount 需格式化的金额
     * @param int $rate 利率
     * @param int $scale 保留小数位数
     * @param string $sep sep
     * @return string
     */
    static public function outputAmount($amount, $rate = 1, $scale = 2, $sep = ',')
    {
        return number_format(bcmul($amount, $rate, ($scale+1)), $scale, '.', $sep);
    }
}