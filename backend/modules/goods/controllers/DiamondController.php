<?php

namespace backend\modules\goods\controllers;

use common\enums\AttrIdEnum;
use Yii;
use common\models\goods\Diamond;
use common\components\Curd;
use common\models\base\SearchModel;
use backend\controllers\BaseController;
use common\helpers\ResultHelper;

/**
* Diamond
*
* Class DiamondController
* @package backend\modules\goods\controllers
*/
class DiamondController extends BaseController
{
    use Curd;

    /**
    * @var Diamond
    */
    public $modelClass = Diamond::class;


    /**
    * 首页
    *
    * @return string
    * @throws \yii\web\NotFoundHttpException
    */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'partialMatchAttributes' => [], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams, ['goods_name','language']);
        $this->setLocalLanguage($searchModel->language);
        $dataProvider->query->joinWith(['lang']);
        $dataProvider->query->andFilterWhere(['like', 'lang.goods_name',$searchModel->goods_name]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionGetGoodsName(){
        $carat = Yii::$app->request->post('carat',null);
        $cert_type_id = Yii::$app->request->post('cert_type',null);
        $shape_id = Yii::$app->request->post('shape',null);
        $color_id = Yii::$app->request->post('color',null);
        $clarity_id = Yii::$app->request->post('clarity',null);

        $carat_str = '';
        if(!empty($carat)){
            $carat_str .= $carat.'ct';
        }
        $languages = Yii::$app->params['languages'];
        $ids = array($cert_type_id,$shape_id,$color_id,$clarity_id);
        $data = array();
        foreach ($languages as $key=>$val){
            $goods_name = $carat_str;
            $language = $key;
            $attr_arr = \Yii::$app->services->goodsAttribute->getAttributeByValueIds($ids, $language);
            if(isset($attr_arr[AttrIdEnum::DIA_SHAPE])){
                $goods_name .= ' '.$attr_arr[AttrIdEnum::DIA_SHAPE];
            }
            if(isset($attr_arr[AttrIdEnum::DIA_COLOR])){
                $goods_name .= ' '.$attr_arr[AttrIdEnum::DIA_COLOR].'色';
            }
            if(isset($attr_arr[AttrIdEnum::DIA_CLARITY])){
                $goods_name .= ' '.$attr_arr[AttrIdEnum::DIA_CLARITY].'净度';
            }
            if(isset($attr_arr[AttrIdEnum::DIA_CERT_TYPE])){
                $goods_name .= ' '.$attr_arr[AttrIdEnum::DIA_CERT_TYPE];
            }
            $data[$language] = $goods_name;
        }

        return ResultHelper::json(200, '保存成功',$data);





    }
}
