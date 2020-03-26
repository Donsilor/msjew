<?php
namespace Omnipay\Paypal;

use common\helpers\FileHelper;

class PaypalLog {
    
    public $logPath = \Yii::getAlias('@runtime') . "/pay-logs/paypal/".date('Ym');
    /**
     * 日志写入
     * @param unknown $message
     * @param unknown $path
     */
    public static function writeLog($message,$fileName = null)
    {   
        $fileName = $fileName ? $fileName :"paypal-" . date('Y-m-d') . ".log";
        $message = "[".date('Y-m-d H:i:s')."]".$message;
        FileHelper::writeLog(self::$logPath."/".$fileName, $message);
    }
    
}