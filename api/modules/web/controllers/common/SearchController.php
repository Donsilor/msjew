<?php

namespace api\modules\web\controllers\common;

use common\enums\StatusEnum;
use api\controllers\OnAuthController;
use common\models\goods\Style;
use common\models\goods\StyleLang;


/**
 * Class ProvincesController
 * @package api\modules\v1\controllers\member
 */
class SearchController extends OnAuthController
{

    /**
     * @var Provinces
     */
    public $modelClass = Style::class;
    protected $authOptional = ['index'];


    /**
     * 款式商品搜索
     * @return array
     */
    public function actionIndex(){
        $sort_map = [
            '1'=>'m.sale_volume desc',//最暢銷
            '2'=>'m.sale_volume asc ',//價格 - 從低到高
            '3'=>'m.sale_volume desc',//價格 - 從高到低
        ];
        $type_id = \Yii::$app->request->get("categoryId");//产品线ID
        $keyword = \Yii::$app->request->get("text");//产品线ID
        $order_type = \Yii::$app->request->get("sortType", 1);//排序方式 1-升序；2-降序;

        //排序
        $order = '';
        if(!empty($order_type)){
            $order = $sort_map[$order_type];
        }

        $fields = ['m.id','lang.style_name','m.goods_images','m.sale_price'];
        $query = Style::find()->alias('m')->select($fields)
            ->leftJoin(StyleLang::tableName().' lang',"m.id=lang.master_id and lang.language='".$this->language."'")
            ->where(['m.status'=>StatusEnum::ENABLED])->orderby($order);

        if(!empty($keyword)){
            $query->andWhere(['or',['like','lang.style_name',$keyword],['=','m.style_sn',$keyword]]);
        }


        $result = $this->pagination($query,$this->page, $this->pageSize);

        foreach($result['data'] as & $val) {
            $arr = array();
            $arr['id'] = $val['id'];
            $arr['categoryId'] = $type_id;
            $arr['coinType'] = $this->currency;
            $arr['goodsImages'] = $val['goods_images'];
            $arr['salePrice'] = $this->exchangeAmount($val['sale_price']);
            $arr['goodsName'] = $val['style_name'];
            $arr['isJoin'] = null;
            $arr['specsModels'] = null;
            $val = $arr;
        }
        return $result;

    }


    
    
    
}