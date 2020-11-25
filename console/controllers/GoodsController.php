<?php

namespace console\controllers;

use yii\console\Controller;
use common\models\goods\Diamond;
use common\models\goods\Style;

/**
 * 商品模块任务处理
 * Class CommendController
 * @package console\controllers
 */
class GoodsController extends Controller
{
    /**
     * 同步裸钻数据到goods
     * @param number $minute
     */
    public function actionSyncDiamondToGoods()
    {
        echo 'Start sync Moissanite to goods ------'.PHP_EOL;
        $total = 0;
        for($page = 1 ; $page <= 100; $page ++) {
            $time = time();
            $diamond_list = Diamond::find()->select(['id','sync_goods_time'])
                ->andWhere(['<','sync_goods_time',$time-1*3600])
                ->limit(100)->all();

            if(empty($diamond_list)) {
                break;
            }
            foreach ($diamond_list as $diamond) {                
                \Yii::$app->services->diamond->syncDiamondToGoods($diamond->id);
                $diamond->sync_goods_time = $time;
                $diamond->save(false);
                $total ++;
                echo "diamond_id:".$diamond->id.PHP_EOL;
            }
        }
        echo 'Sync num:'.$total.PHP_EOL;
        echo 'End sync------'.PHP_EOL;
    }
    /**
     * 分解同步款式信息 到 goods
     */
    public function actionSyncStyleToGoods()
    {
        echo 'Start sync style to goods ------'.PHP_EOL;
        $total = 0;
        for($page = 1 ; $page <= 10; $page ++) {
            //查询非裸钻款式列表
            $style_list = Style::find()->select(['id','sync_goods_time'])
                ->andWhere(['<','sync_goods_time',time()-1*3600])
                ->andWhere(['not in','type_id',[15]])
                ->limit(100)->all();
            
            if(empty($style_list)) {
                break;
            }
            foreach ($style_list as $style) {
                \Yii::$app->services->goods->syncStyleToGoods($style->id);
                $style->sync_goods_time = time();
                $style->save(false);
                
                $total ++;
                echo "style_id:".$style->id.PHP_EOL;
            }
        }
        echo 'Sync num:'.$total.PHP_EOL;
        echo 'End sync------'.PHP_EOL;
    }
}