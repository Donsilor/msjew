<?php


namespace common\components;

trait outputFormat
{
    /**
     * @param string $name 金额
     * @param int $rate 汇率
     * @param int $scale 精度
     * @param string $sep 逗号
     * @return string
     */
    public function asAmount($name, $rate = 1, $scale = 2, $sep = ',')
    {
        $value = $this->getAttribute($name);
        $rate = is_string($rate) && $this->hasAttribute($rate) ? $this->getAttribute($rate) : $rate;
        return number_format(bcmul($value, $rate, ($scale+2)), $scale, '.', $sep);
    }
}