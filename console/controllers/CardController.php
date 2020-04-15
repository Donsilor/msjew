<?php

namespace console\controllers;

use common\models\market\MarketCard;
use yii\console\Controller;
use yii\helpers\BaseConsole;
use yii\helpers\Console;

/**
 * 购物卡命令
 * Class CardController
 * @package console\controllers
 */
class CardController extends Controller
{

    /**
     * 导入购物卡
     */
    public function actionImport()
    {
        $allArgs = func_get_args();

    }

    /**
     * 导出购物卡
     */
    public function actionExport()
    {
        $allArgs = func_get_args();
        $batchs = implode(',', $allArgs);

        $batchs = str_replace(['，', ' '], [',', ''], $batchs);
        $batchs = explode(',', $batchs);

        $batchList = [];
        foreach ($batchs as $batch) {
            if(empty($batch)) {
                continue;
            }
            $batchList[] = $batch;
            BaseConsole::output($batch.'：'.MarketCard::find()->where(['batch'=>$batch])->count('id').' 条记录');
        }

        if(!$this->confirm("请确定批次号记录条数是否正确?")) {
            exit("退出\n");
        }

        $fileName = \yii\helpers\BaseConsole::input("请输入导出文件名：");

        $cardList = MarketCard::find()->where(['batch'=>$batchList])->all();
        $count = count($cardList);
        Console::startProgress(0, $count);

        $progress = 0;

        foreach ($cardList as $n => $card) {
            $time = time();

            //第5秒更新一次进度
            if($progress!= $time && $time%6==0) {
                $progress = $time;
                Console::updateProgress($n, $count);
            }

            $cardInfo = sprintf('%s,%s,%s',$card['batch'], $card['sn'], $card->getPassword());

            file_put_contents($fileName, $cardInfo . "\r\n",FILE_APPEND);
        }
        Console::updateProgress($count, $count);
        Console::endProgress();

        BaseConsole::output('导出完成');
    }
}