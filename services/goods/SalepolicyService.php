<?php

namespace services\goods;
use common\components\Service;
use common\models\goods\Style;
use common\models\goods\StyleMarkup;
use common\models\goods\GoodsMarkup;
use common\enums\StatusEnum;
use common\helpers\AmountHelper;
use common\enums\AreaEnum;

/**
 * 销售政策(加价率)
 * Class SalepolicyService
 * @package services\common
 */
class SalepolicyService extends Service
{   
    /**
     * 款式加价率
     * @param int $style_id
     * @return boolean
     */
    public function createStyleMarkup($style_id)
    {
        $style = Style::find()->where(['id'=>$style_id])->one();
        $markup_list = json_decode($style->style_salepolicy,true);
        
        if(empty($markup_list)) {
            $markup_list = [];
            foreach (AreaEnum::getMap() as $area_id=>$area_name) {
                $markup_rate  = 1;
                $markup_value =  0;
                $sale_price = AmountHelper::calcMarkupPrice($style->sale_price,$markup_rate,$markup_value,2);
                $markup_list[$area_id] = [
                    'area_id' =>$area_id,
                    'area_name'=>$area_name,
                    'sale_price'=>$sale_price,
                    'markup_rate' => $markup_rate,
                    'markup_value'=> $markup_value,
                    'status'=> StatusEnum::ENABLED,
                ];
            } 
            $style->style_salepolicy = json_encode($markup_list);
        }
        
        $base_price = $style->sale_price;
        foreach ($markup_list as $markup) {
            $area_id = (int)$markup['area_id'];
            $styleMarkup = StyleMarkup::find()->where(['style_id'=>$style_id,'area_id'=>$area_id])->one();
            if(!$styleMarkup) {
                $styleMarkup = new StyleMarkup();
                $styleMarkup->style_id = $style_id;
                $styleMarkup->area_id  = $area_id;
            }
            $markup_rate = $markup['markup_rate'] ?? 1;
            $markup_value= $markup['markup_value'] ?? 0;
            $status = $markup['status'] ?? StatusEnum::ENABLED;
            $sale_price = AmountHelper::calcMarkupPrice($base_price, $markup_rate, $markup_value,2);
            $styleMarkup->sale_price = $sale_price;
            $styleMarkup->markup_rate = $markup_rate;
            $styleMarkup->markup_value = $markup_value;
            $styleMarkup->status = $status;
            $styleMarkup->save();
        }
        $style->save(false);
        
    }
    /**
     * 商品加价率
     * @param int $goods_id
     * @param int $style_id
     * @param decimal $base_price
     */
    public function createGoodsMarkup($goods_id,$style_id,$base_price) 
    {
        $markup_list = StyleMarkup::find()->where(['style_id'=>$style_id])->all();
        
        foreach ($markup_list as $markup) {
            $markup_id = $markup->id;
            $markup_rate  = $markup->markup_rate;
            $markup_value = $markup->markup_value;
            $area_id = $markup->area_id;
            
            $sale_price = AmountHelper::calcMarkupPrice($base_price, $markup_rate, $markup_value,2);
            
            $goodsMarkup = GoodsMarkup::find()->where(['goods_id'=>$goods_id,'area_id'=>$area_id])->one();
            if(!$goodsMarkup) {
                 $goodsMarkup = new GoodsMarkup();
                 $goodsMarkup->markup_id = $markup_id;
                 $goodsMarkup->goods_id  = $goods_id;
                 $goodsMarkup->area_id  = $markup->area_id;
            }
            $goodsMarkup->sale_price = $sale_price;
            $goodsMarkup->save(false);
        }
        
        
    }
}