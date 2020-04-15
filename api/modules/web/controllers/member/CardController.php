<?php

namespace api\modules\web\controllers\member;

use api\modules\web\forms\CardForm;
use common\helpers\ImageHelper;
use common\models\forms\PayForm;
use common\models\goods\Ring;
use common\models\goods\RingLang;
use common\models\market\MarketCard;
use common\models\market\MarketCardDetails;
use common\models\order\Order;
use common\models\order\OrderCart;
use api\modules\web\forms\CartForm;
use common\helpers\ResultHelper;
use api\controllers\UserAuthController;
use services\goods\TypeService;
use services\market\CardService;
use yii\base\Exception;
use yii\web\UnprocessableEntityHttpException;

/**
 * 购物卡
 *
 * Class CardController
 * @package api\modules\v1\controllers
 */
class CardController extends UserAuthController
{
    
    public $modelClass = MarketCardDetails::class;
    
    protected $authOptional = [];

    /**
     * 购物车列表     
//     */
//    public function actionIndex()
//    {
//        $post = \Yii::$app->request->post();
//
//        $model = new CardForm();
//        $model->setAttributes($post);
//
//        if(!$model->validate()) {
//            return ResultHelper::api(422, $this->getError($model));
//        }
//
//        $query = $this->modelClass::find()->where(['card_id'=>$model->getCard()->id]);
//
//        $query->orderBy('id DESC');
//
//        return $this->pagination($query, $this->page, $this->pageSize,true);
//    }

    /**
     * 验证购物卡
     */
    public function actionVerify()
    {

        $post = \Yii::$app->request->post();

        $model = new CardForm();
        $model->setAttributes($post);

        if(!$model->validate()) {
            return ResultHelper::api(422, $this->getError($model));
        }

        if(!empty($post['test'])) {
            //状态，是否过期，是否有余额


            exit;


            return;
        }

        $data = [
            'sn' => $model->getCard()->sn,
            'currency' => $this->getCurrency(),
            'amount' => $this->exchangeAmount($model->getCard()->amount),
            'goodsTypeAttach' => $model->getCard()->goods_type_attach,
            'balance' => $this->exchangeAmount($model->getCard()->balance),
            'startTime' => $model->getCard()->start_time,
            'endTime' => $model->getCard()->end_time,
            'status' => $model->getCard()->status
        ];

        $goodsTypes = [];
        foreach (TypeService::getTypeList() as $key => $item) {
            if(in_array($key, $data['goodsTypeAttach'])) {
                $goodsTypes[$key] = $item;
            }
        }
        $data['goodsTypes'] = $goodsTypes;

        return $data;
    }
}