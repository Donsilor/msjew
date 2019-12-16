<?php

namespace api\modules\v1\controllers\common;

use Yii;
use api\controllers\OnAuthController;
use common\models\common\Menu;

/**
 * Class ProvincesController
 * @package api\modules\v1\controllers\member
 * @author jianyan74 <751393839@qq.com>
 */
class MenuController extends OnAuthController
{
    /**
     * @var Provinces
     */
    public $modelClass = Menu::class;
    
    /**
     * 获取省市区
     *
     * @param int $pid
     * @return array|yii\data\ActiveDataProvider
     */
    public function actionIndex()
    {
        return '111';
        $pid = Yii::$app->request->get('pid', 0);
        
        return Yii::$app->services->menu->getOnAuthList();
    }
}