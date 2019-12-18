<?php

namespace api\modules\v1\controllers\common;

use common\enums\StatusEnum;
use common\helpers\ResultHelper;
use common\models\common\AdvertImages;
use common\models\common\AdvertImagesLang;
use Yii;
use api\controllers\OnAuthController;

/**
 * Class ProvincesController
 * @package api\modules\v1\controllers\member
 * @author jianyan74 <751393839@qq.com>
 */
class AdvertImagesController extends OnAuthController
{
    /**
     * @var Provinces
     */
    public $modelClass = AdvertImages::class;
    protected $authOptional = ['index'];
    /**
     * 根据分类ID获取广告图
     *
     * @param int $pid
     * @return array|yii\data\ActiveDataProvider
     */
    public function actionIndex()
    {
        $adv_id = Yii::$app->request->get('adv_id',null);
        if($adv_id == null) return ResultHelper::api(400, '参数错误');
        $language = Yii::$app->params['language'];
        $time = date('Y-m-d H:i:s', time());
        $model = $this->modelClass::find()->alias('m')
            ->where(['m.status' => StatusEnum::ENABLED, 'm.adv_id'=>$adv_id])
            ->andWhere(['and',['<=','start_time',$time], ['>=','end_time',$time]])
            ->leftJoin(AdvertImagesLang::tableName().' lang','lang.master_id = m.id and lang.language =  "'.$language.'"')
            ->select(['lang.title as title','lang.adv_image','adv_url'])
            ->orderby('m.sort desc, m.created_at desc')
            ->asArray()
            ->all();
        return $model;
    }


}