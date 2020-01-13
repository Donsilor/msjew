<?php

namespace api\modules\wap\controllers\common;

use Yii;
use api\controllers\OnAuthController;
use common\models\common\Area;

/**
 * Class ProvincesController
 * @package api\modules\v1\controllers\member
 * @author jianyan74 <751393839@qq.com>
 */
class AreaController extends OnAuthController
{
    /**
     * @var Provinces
     */
    public $modelClass = Area::class;
    protected $authOptional = ['index'];
    /**
     * 获取省市区
     *
     * @param int $pid
     * @return array|yii\data\ActiveDataProvider
     */
    public function actionIndex()
    {

        $pid = Yii::$app->request->get('pid');  
        $name ="name_".strtolower(str_replace('-','_',$this->language)); 
        
        $query = $this->modelClass::find()->select(['id as areaId', $name." as areaName"]);
        if(empty($pid)){
            $query->andWhere(['level'=>2]);
        }else {
            $query->andWhere(['pid'=>$pid]);
        }            
        
        return $query->orderBy('sort asc')->cache(600)->asArray()->all();

    }
}