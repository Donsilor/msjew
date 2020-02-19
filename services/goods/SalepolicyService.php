<?php

namespace services\goods;
use common\components\Service;
use common\models\goods\Style;
use common\models\StyleMarkup;
use common\enums\StatusEnum;
use common\models\GoodsMarkup;

/**
 * Class GoodsService
 * @package services\common
 */
class SalepolicyService extends Service
{   
    /**
     * 款式加价率
     * @param unknown $style_id
     * @return boolean
     */
    public function createStyleMarkup($style_id)
    {
        $style = Style::find()->where(['id'=>$style_id])->one();
        $markup_list = json_decode($style->style_salepolicy,true);
        
        if(!is_array($markup_list)) return false;
        
        foreach ($markup_list as $vo) {
            $area_id = (int)$vo['area_id'];
            $markup = StyleMarkup::find()->where(['style_id'=>$style_id,'area_id'=>$area_id])->one();
            if($markup) {
                $markup = new StyleMarkup();
                $markup->style_id = $style_id;
                $markup->area_id  = $area_id;
            }
            $markup->attributes = $vo; 
            $markup->save();
        }
    }
    /**
     * 商品加价率
     * @param int $goods_id
     * @param int $style_id
     * @param decimal $base_price
     */
    public function createGoodsMarkup($goods_id,$style_id,$base_price) 
    {
        $markup_list = StyleMarkup::find()->where(['style_id'=>$style_id,'status'=>StatusEnum::ENABLED])->all();
        
        foreach ($markup_list as $markup) {
            $markup_id = $markup->id;
            $markup_rate  = $markup->markup_rate;
            $markup_value = $markup->markup_value;
            
            $sale_price = bcadd($base_price * $markup_rate,$markup_value);
            $goodsMarkup = GoodsMarkup::find()->where(['markup_id'=>$markup_id,'goods_id'=>$goods_id])->one();
            if(!$goodsMarkup) {
                 $goodsMarkup = new GoodsMarkup();
                 $goodsMarkup->markup_id = $markup_id;
                 $goodsMarkup->goods_id  = $goods_id;
                 $goodsMarkup->area_id  = $markup->area_id;
            }
            $goodsMarkup->sale_price = $sale_price;
            $goodsMarkup->save();
        }
        
        
    }
}