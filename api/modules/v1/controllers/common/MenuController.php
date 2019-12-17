<?php

namespace api\modules\v1\controllers\common;

use Yii;
use api\controllers\OnAuthController;
use common\models\common\Menu;
//use api\controllers\ActiveController;

/**
 * Class ProvincesController
 * @package api\modules\v1\controllers\member
 */
class MenuController extends OnAuthController
{
    /**
     * @var Provinces
     */
    public $modelClass = Menu::class;
    protected $authOptional = ['index'];
    /**
     * 获取省市区
     *
     * @param int $pid
     * @return array|yii\data\ActiveDataProvider
     */
    public function actionIndex()
    {        
        return Yii::$app->services->menu->getSimpleList();
    }
    
}