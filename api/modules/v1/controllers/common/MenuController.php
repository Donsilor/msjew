<?php

namespace api\modules\v1\controllers\common;

use Yii;
use api\controllers\OnAuthController;
use common\models\common\Menu;
use common\helpers\ArrayHelper;
use common\helpers\StringHelper;
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
        $models = Yii::$app->services->menu->getFrontList(6);        
        $models = ArrayHelper::itemsMerge($models,0,'id','pid','items');
        /* foreach ($models as &$m1) {
            if(empty($m1['items'])) continue;            
            $cate1 = $m1['title'];
            foreach ($m1['items'] as &$m2){                
                if(empty($m2['items'])) continue;
                foreach ($m2['items'] as &$m3){
                    $cate3 = str_replace(' ','_',StringHelper::trim($m3['title']));
                    $m3['url'] = str_replace("/goods-list/?",'/category/'.$cate3.'-'.$cate1.'/', $m3['url']);
                }
            }
        } */
        return $models;
    }
    
}