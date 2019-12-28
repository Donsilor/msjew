<?php

namespace api\modules\web\controllers\common;

use common\enums\StatusEnum;
use common\helpers\ResultHelper;
use common\models\common\Advert;
use common\models\common\AdvertImages;
use common\models\common\AdvertImagesLang;
use common\models\goods\StyleLang;
use common\models\goods\Type;
use PhpOffice\PhpSpreadsheet\Style\Style;
use Yii;
use api\controllers\OnAuthController;

/**
 * Class ProvincesController
 * @package api\modules\v1\controllers\member
 * @author jianyan74 <751393839@qq.com>
 */
class DefaultController extends OnAuthController
{
    /**
     * @var Provinces
     */
    public $modelClass = AdvertImages::class;
    protected $authOptional = ['ad','type-ad'];
    /**
     * 根据分类ID获取广告图
     *
     * @param int $pid
     * @return array|yii\data\ActiveDataProvider
     */
    public function actionAd()
    {
        $adv_id = Yii::$app->request->get('adv_id',null);
        if($adv_id == null) return ResultHelper::api(400, '缺省参数');
        $language = Yii::$app->params['language'];
        $time = date('Y-m-d H:i:s', time());
        $model = $this->modelClass::find()->alias('m')
            ->where(['m.status' => StatusEnum::ENABLED, 'm.adv_id'=>$adv_id])
            ->andWhere(['or',['and',['<=','m.start_time',$time], ['>=','m.end_time',$time]],['m.end_time'=>null]])
            ->leftJoin(AdvertImagesLang::tableName().' lang','lang.master_id = m.id and lang.language =  "'.$language.'"')
            ->select(['lang.title as title','lang.adv_image','adv_url'])
            ->orderby('m.sort desc, m.created_at desc')
            ->asArray()
            ->all();
        return $model;
    }



    /**
     * 根据产品线ID获取Banner
     *
     * @param int $pid
     * @return array|yii\data\ActiveDataProvider
     */
    public function actionTypeAd()
    {
        $type_id = Yii::$app->request->get('type_id');
        $adv_id = Yii::$app->request->get('adv_id');
        if($type_id == null) {
            return ResultHelper::api(400, '产品线不能为空');
        }
        if($adv_id == null) {
            return ResultHelper::api(400, '广告位置ID不能为空');
        }
        $language = Yii::$app->params['language'];
        $model = \Yii::$app->services->advert->getTypeAdvertImage($type_id,$adv_id,$language);
        return $model;
    }

    //首页商品推荐
    public function actionRecommend(){
        $type_id = 12 ;
        $limit = \Yii::$app->request->get("limit",4);//查询数量
        $fields = ['m.id', 'm.goods_images', 'lang.style_name','m.sale_price'];
        $result = Style::find()->alias('m')->select($fields)
            ->leftJoin(StyleLang::tableName().' lang',"m.id=lang.master_id and lang.language='".$this->language."'")
            ->where(['and',['m.type_id'=>$type_id],['m.status'=>StatusEnum::ENABLED]])
            ->orderBy('')
            ->limit($limit)->asArray()->all();
        foreach($result as & $val) {
            $val['type_id'] = $type_id;
            $val['currency'] = $this->currency;
        }
        return $result;

    }






}