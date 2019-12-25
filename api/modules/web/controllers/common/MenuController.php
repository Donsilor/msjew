<?php

namespace api\modules\web\controllers\common;

use Yii;
use api\controllers\OnAuthController;
use common\models\common\Menu;
use common\helpers\ArrayHelper;


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
     * 前台菜单
     *
     * @param int $pid
     * @return array|yii\data\ActiveDataProvider
     */
    public function actionIndex()
    {   
        $cat_id = 6;
        $models = Yii::$app->services->menu->getFrontList($cat_id);
        $models = ArrayHelper::itemsMerge($models,0,'id','pid','items');
        return $models;
    }
    
}