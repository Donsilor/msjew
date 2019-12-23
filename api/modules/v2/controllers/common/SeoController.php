<?php

namespace api\modules\v2\controllers\common;

use common\enums\StatusEnum;
use common\helpers\ResultHelper;
use common\models\common\AdvertImages;
use common\models\common\AdvertImagesLang;
use common\models\common\WebSeo;
use common\models\common\WebSeoLang;
use Yii;
use api\controllers\OnAuthController;

/**
 * Class ProvincesController
 * @package api\modules\v1\controllers\member
 * @author jianyan74 <751393839@qq.com>
 */
class SeoController extends OnAuthController
{
    /**
     * @var Provinces
     */
    public $modelClass = WebSeo::class;
    protected $authOptional = ['index'];
    /**
     * 根据分类ID获取广告图
     *
     * @param int $pid
     * @return array|yii\data\ActiveDataProvider
     */
    public function actionIndex()
    {
        $id = Yii::$app->request->get('id',null);
        if($id == null) return ResultHelper::api(400, '缺省参数');
        $language = Yii::$app->params['language'];
        $model = $this->modelClass::find()->alias('m')
            ->where(['m.id'=>$id])
            ->leftJoin(WebSeoLang::tableName().' lang','lang.master_id = m.id and lang.language =  "'.$language.'"')
            ->select(['lang.*'])
            ->asArray()
            ->one();
        return $model;
    }


}