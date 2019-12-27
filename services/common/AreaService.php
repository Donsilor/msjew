<?php

namespace services\common;

use Yii;
use common\models\common\Area;
use common\components\Service;


/**
 * Class ProvincesService
 * @package services\common
 * @author jianyan74 <751393839@qq.com>
 */
class AreaService extends Service
{
   
    /**
     * 根据id数组获取区域名称
     *
     * @param $id
     * @return mixed
     */
    public function getAreaListName(array $ids,$language = null)
    {
        if($language == null) {
            $language = \Yii::$app->params['language'];
        }
        $name ="name_".strtolower(str_replace('-','_',$language)); 
        $areas = Area::find()->select(['id',$name." as name"])->where(['in', 'id', $ids])->orderBy('id asc')->asArray()->all();
        if ($areas) {
            $address = '';            
            foreach ($areas as $area) {
                $address .= $area['name'] . ' ';
            }            
            return $address;
        }
        
        return false;
    }
    
}