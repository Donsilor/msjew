<?php
namespace Omnipay\Paypal;

use common\helpers\FileHelper;

class PaypalLog {
    
    const LOG_PATH = \Yii::getAlias('@runtime') . "/pay-logs/paypal/".date('Ym');
    /**
     * 日志写入
     * @param unknown $message
     * @param unknown $path
     */
    public static function writeLog($message,$file = null)
    {   
        $filePath = $file ? self::LOG_PATH."/".$file:self::LOG_PATH."/paypal-" . date('Y-m-d') . ".log";
        $message = "[".date('Y-m-d H:i:s')."]".$message;
        FileHelper::writeLog($filePath, $message);
    }
    
}