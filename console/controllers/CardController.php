<?php

namespace console\controllers;

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


        //进度条效果
//        Console::startProgress(0, 100);
//        for ($n = 1; $n <= 100; $n++){
////            sleep(1);
//            if($n%10==0)
//            Console::updateProgress($n, 100);
//        }
//        Console::endProgress();





        //输入提示符
        BaseConsole::output('请输入导出数据批次号，多个批次号请用逗号隔开');
        $batch = trim(\yii\helpers\BaseConsole::input("批次号:"));
        $batch = str_replace(['，'], [','], $batch);

        if(!$this->confirm("确定输入正确吗?")) {
            exit("退出\n");
        }
        echo "你输入的姓名是:$name\n";
        echo "你输入的年龄是:$age\n";
    }
}