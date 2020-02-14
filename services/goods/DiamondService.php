<?php

namespace services\goods;
use common\components\Service;
use common\models\goods\Diamond;
use yii\db\Expression;



/**
 * Class DiamondService
 * @package services\common
 */
class DiamondService extends Service
{
    /**
     * 更改基本库存
     * @param unknown $goods_id
     * @param unknown $quantity
     * @param unknown $for_sale 是否销售库存
     */
    public function updateGoodsStorageForOrder($goods_id,$quantity)
    {
        $data = [
            'goods_storage'=> new Expression("goods_storage+{$quantity}"),
            'sale_volume'  => new Expression("sale_volume-{$quantity}")
        ];
        Diamond::updateAll($data,['goods_id'=>$goods_id]);
    }    

}