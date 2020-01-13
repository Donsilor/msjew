<?php


namespace common\components;

trait outputFormat
{
    public function asAmount($name, $rate = 1, $scale = 2, $sep = ',')//金额，精度，汇率，逗号
    {
        $value = $this->getAttribute($name);
        $rate = is_string($rate) && $this->hasAttribute($rate) ? $this->getAttribute($rate) : $rate;
        return number_format(bcmul($value, $rate, ($scale+2)), $scale, '.', $sep);
    }
}