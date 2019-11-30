<?php

namespace backend\modules\setting\controllers;


use common\models\setting\Advert;
use common\models\setting\AdvertLang;
use function Complex\negative;
use services\common\AdvertService;
use Yii;
use common\models\setting\AdvertImages;
use common\components\Curd;
use common\models\base\SearchModel;
use backend\controllers\BaseController;

/**
* AdvertImages
*
* Class AdvertImagesController
* @package backend\modules\setting\controllers
*/
class AdvertImagesController extends BaseController
{
    protected $adv_id;
    use Curd;

    /**
    * @var AdvertImages
    */
    public $modelClass = AdvertImages::class;

    public function init()
    {
        $this->adv_id = Yii::$app->request->get('adv_id');

        parent::init();
    }

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
            ->search(Yii::$app->request->queryParams);

        if(!empty($this->start_end)) {
            $dataProvider->query->andWhere('>=','start_time', explode('/',$this->start_end)[0]);//起始时间
            $dataProvider->query->andWhere('<','end_time', (explode('/',$this->start_end)[1]));//结束时间
            }




        $dataProvider->query->andWhere(['>','status',-1]);

        $AdvertService = new AdvertService();
        //获取广告位
        $advert = $AdvertService->getDropDown(Yii::$app->language);




        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'adv_id' => $this->adv_id,
            'advert' =>$advert,
            'searchModel' => $searchModel,

        ]);
    }



    /**
     * ajax编辑/创建
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionAjaxEditLang()
    {
        $id = Yii::$app->request->get('id');
        //$trans = Yii::$app->db->beginTransaction();
        $model = $this->findModel($id);
        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            if($model->save()){
                //多语言编辑
                $this->editLang($model,true);
                return $this->redirect(['advert-images/index']);
            }else{
                return $this->message($this->getError($model), $this->redirect(['advert-images/index']), 'error');
            }
        }

        $AdvertService = new AdvertService();
        $advert = $AdvertService->getDropDown(Yii::$app->language);
        return $this->renderAjax($this->action->id, [
            'model' => $model,
            'advert' =>$advert,
        ]);
    }


    /**
     * 删除
     * @param unknown $id
     * @return mixed|string
     */
    public function actionDelete($id)
    {
        if ($model = $this->findModel($id)) {
            $model->status = -1;
            $model->save();
            return $this->message("删除成功", $this->redirect(['index', 'id' => $model->id]));
        }

        return $this->message("删除失败", $this->redirect(['index', 'id' => $model->id]), 'error');
    }


    //重写批量删除方式
    public function batchDelete($ids = [],$status = -1){
        foreach ($ids as $k=>$v){
            $model = $this->findModel($v);
            $model->status = $status;
            if(!$model->save(false))
                return new BadRequestHttpException('操作失败！');
        }
        return true;
    }



    /**
     * 返回模型
     *
     * @param $id
     * @return mixed
     */
    protected function findModel($id)
    {
        if (empty($id) || empty(($model = AdvertImages::findOne($id)))) {
            $model = new AdvertImages;
            $model = $model->loadDefaultValues();
            $model->adv_id = $this->adv_id;
            return $model;
        }

        return $model;
    }





}
