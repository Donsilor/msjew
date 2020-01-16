<?php


namespace common\helpers;


class AmountHelper
{
    static public function outputAmount($amount, $rate = 1, $scale = 2, $sep = ',')
    {
        return number_format(bcmul($amount, $rate, ($scale+1)), $scale, '.', $sep);
    }
}