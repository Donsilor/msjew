<?php

namespace backend\modules\common\controllers;


use common\models\common\Advert;
use common\models\common\AdvertLang;
use common\models\goods\Type;
use function Complex\negative;
use services\common\AdvertService;
use Yii;
use common\models\common\AdvertImages;
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
            ->search(Yii::$app->request->queryParams,['title','start_end','adv_type']);
        $dataProvider->query->joinWith(['lang']);
        $dataProvider->query->andFilterWhere(['like', 'lang.title',$searchModel->title]) ;
        $dataProvider->query->andWhere(['>','common_advert_images.status',-1]);

        $dataProvider->query->joinWith(['cate']);
        $adv_type = $searchModel->adv_type;
        if(!empty($adv_type)){
            $dataProvider->query->andWhere(['=','adv_type',$searchModel->adv_type]);
        }

        $AdvertService = new AdvertService();
        //获取广告位
        $advert = $AdvertService->getDropDown(Yii::$app->language);

        //获取产品线
        $type = Type::getDropDown();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'adv_id' => $this->adv_id,
            'advert' =>$advert,
            'type' =>$type,
            'searchModel' => $searchModel,

        ]);
    }

    /**
     * 首页
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionBanner()
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
            ->search(Yii::$app->request->queryParams,['title','start_end']);
        $dataProvider->query->andWhere(['type'=>2]);
        $dataProvider->query->joinWith(['lang']);
        $dataProvider->query->andFilterWhere(['like', 'lang.title',$searchModel->title]) ;

        $dataProvider->query->andWhere(['>','status',-1]);


        //获取产品线
        $type = Type::getDropDown();


        return $this->render('banner', [
            'dataProvider' => $dataProvider,
            'adv_id' => $this->adv_id,
            'type' =>$type,
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

                //更新区域
                \Yii::$app->services->advert->createAdverArea($model->id);
                return $this->redirect(['advert-images/index']);
            }else{
                return $this->message($this->getError($model), $this->redirect(['advert-images/index']), 'error');
            }
        }
        //获取产品线
        $type = Type::getDropDown();

        $AdvertService = new AdvertService();
        $advert = $AdvertService->getDropDown(Yii::$app->language);
        return $this->renderAjax($this->action->id, [
            'model' => $model,
            'advert' =>$advert,
            'type' =>$type,
        ]);
    }



    /**
     * ajax编辑/创建
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionBannerEditLang()
    {
        $id = Yii::$app->request->get('id');
        //$trans = Yii::$app->db->beginTransaction();
        $model = $this->findModel($id);
        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            $model->type = 2;
            if($model->save()){
                //多语言编辑
                $this->editLang($model,true);
                return $this->redirect(['advert-images/banner']);
            }else{
                return $this->message($this->getError($model), $this->redirect(['advert-images/banner']), 'error');
            }
        }

        return $this->renderAjax($this->action->id, [
            'model' => $model,
            'type' =>Type::getDropDown(),
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
