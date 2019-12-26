<?php

namespace api\modules\web\controllers\member;

use api\controllers\UserAuthController;
use common\models\member\Address;

/**
 * 收货地址
 *
 * Class AddressController
 * @package api\modules\v1\controllers\member
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class AddressController extends UserAuthController
{
    /**
     * @var Address
     */
    public $modelClass = Address::class;
    

    public function actionIndex()
    {
        return $this->findModels();
    }
    /**
     * 详情
     * @return mixed|\api\controllers\NULL|\yii\db\ActiveRecord
     */
    public function actionInfo()
    { 
        return $this->info();
    }
    /**
     * 添加
     * @return mixed|\api\controllers\NULL|\yii\db\ActiveRecord
     */
    public function actionAdd()
    {
        return $this->add();
    }
    /**
     * 编辑
     * @return mixed|\api\controllers\NULL|array
     */
    public function actionEdit()
    {
        return $this->edit();
    }
    /**
     * 删除
     * @return mixed|\api\controllers\NULL|\api\controllers\unknown[]
     */
    public function actionDel()
    {
        return $this->del();
    }
    
}
