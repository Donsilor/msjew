<?php

namespace backend\modules\common\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use common\components\Curd;
use common\models\common\Menu;
use common\enums\AppEnum;
use backend\controllers\BaseController;
use common\models\common\MenuLang;

/**
 * Class MenuController
 * @package backend\modules\base\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class MenuController extends BaseController
{
    use Curd;

    /**
     * @var \yii\db\ActiveRecord
     */
    public $modelClass = Menu::class;

    /**
     * Lists all Tree models.
     * @return mixed
     */
    public function actionIndex()
    {
        $cate_id = Yii::$app->request->get('cate_id', Yii::$app->services->menuCate->findFirstId(AppEnum::BACKEND));
        $title = Yii::$app->request->get('title');
        $query = $this->modelClass::find()->alias('a')
            ->leftJoin(MenuLang::tableName().' lang','lang.master_id = a.id and lang.language = "'.Yii::$app->params['language'].'"')
            ->orderBy('sort asc, a.id asc')
            ->filterWhere(['cate_id' => $cate_id])
            ->andWhere(['app_id' => AppEnum::BACKEND]);
            //echo $query->createCommand()->getRawSql();
        if(!empty($title)){
            $query->andWhere(['or',['=','a.id',$title],['like','lang.title',$title],['like','a.title',$title]]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'cates' => Yii::$app->services->menuCate->findDefault(AppEnum::BACKEND),
            'cate_id' => $cate_id,
        ]);
    }

    
    /**
     * 编辑/创建
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionAjaxEdit()
    {
        $id = Yii::$app->request->get('id', '');
        $model = $this->findModel($id);
        $model->pid = Yii::$app->request->get('pid', null) ?? $model->pid; // 父id
        $model->cate_id = Yii::$app->request->get('cate_id', null) ?? $model->cate_id; // 分类id

        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            return $model->save()
                ? $this->redirect(['index', 'cate_id' => $model->cate_id])
                : $this->message($this->getError($model), $this->redirect(['index', 'cate_id' => $model->cate_id]), 'error');
        }

        if ($model->isNewRecord && $model->parent) {
            $model->cate_id = $model->parent->cate_id;
        }

        $menuCate = Yii::$app->services->menuCate->findById($model->cate_id);


        return $this->renderAjax('ajax-edit', [
            'model' => $model,
            'cates' => Yii::$app->services->menuCate->getDefaultMap(AppEnum::BACKEND),
            'menuDropDownList' => Yii::$app->services->menu->getDropDown($menuCate, AppEnum::BACKEND, $id),
        ]);
    }
    /**
     * 前端菜单：编辑/创建
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionAjaxEditLang()
    {
        $id = Yii::$app->request->get('id', '');
        $model = $this->findModel($id);
        $model->pid = Yii::$app->request->get('pid', null) ?? $model->pid; // 父id
        $model->cate_id = Yii::$app->request->get('cate_id', null) ?? $model->cate_id; // 分类id

        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            if($model->save()){
                $this->editLang($model);
                $this->redirect(['index', 'cate_id' => $model->cate_id]);
            }else{
                $this->message($this->getError($model), $this->redirect(['index', 'cate_id' => $model->cate_id]), 'error');
            }

        }

        if ($model->isNewRecord && $model->parent) {
            $model->cate_id = $model->parent->cate_id;
        }

        $menuCate = Yii::$app->services->menuCate->findById($model->cate_id);
        return $this->renderAjax('ajax-edit-lang', [
            'model' => $model,
            'cates' => Yii::$app->services->menuCate->getDefaultMap(AppEnum::BACKEND),
            'menuDropDownList' => Yii::$app->services->menu->getDropDown($menuCate, AppEnum::BACKEND, $id),
        ]);
    }
    
    /**
     * 前端菜单：列表
     * @return mixed
     */
    public function actionFrontIndex()
    {
        $cate_id = Yii::$app->request->get('cate_id', Yii::$app->services->menuCate->findFirstId(AppEnum::API));
        
        $query = $this->modelClass::find()->alias('a')
            ->leftJoin(MenuLang::tableName().' lang','lang.master_id = a.id and lang.language = "'.Yii::$app->params['language'].'"')
            ->orderBy('sort asc, a.id asc')
            ->filterWhere(['cate_id' => $cate_id])
            ->andWhere(['app_id' => AppEnum::API]);
        //echo $query->createCommand()->getRawSql();
        
        $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => false
        ]);
        
        return $this->render('front-index', [
                'dataProvider' => $dataProvider,
                'cates' => Yii::$app->services->menuCate->findDefault(AppEnum::API),
                'cate_id' => $cate_id,
        ]);
    }
    
    /**
     * 编辑/创建
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionFrontEditLang()
    {
        $id = Yii::$app->request->get('id', '');
        $model = $this->findModel($id);
        $model->pid = Yii::$app->request->get('pid', null) ?? $model->pid; // 父id
        $model->cate_id = Yii::$app->request->get('cate_id', null) ?? $model->cate_id; // 分类id
        
        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            if($model->save()){
                $this->editLang($model);
                $this->redirect(['front-index', 'cate_id' => $model->cate_id]);
            }else{
                $this->message($this->getError($model), $this->redirect(['front-index', 'cate_id' => $model->cate_id]), 'error');
            }
            
        }
        
        if ($model->isNewRecord && $model->parent) {
            $model->cate_id = $model->parent->cate_id;
        }
        
        $menuCate = Yii::$app->services->menuCate->findById($model->cate_id);
        return $this->renderAjax('ajax-edit-lang', [
                'model' => $model,
                'cates' => Yii::$app->services->menuCate->getDefaultMap(AppEnum::API),
                'menuDropDownList' => Yii::$app->services->menu->getDropDown($menuCate, AppEnum::API, $id),
        ]);
    }

    /**
     * 删除
     *
     * @param $id
     * @return mixed
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        if (($model = $this->findModel($id))->delete()) {
            return $this->message("删除成功", $this->redirect(['index', 'cate_id' => $model->cate_id]));
        }

        return $this->message("删除失败", $this->redirect(['index', 'cate_id' => $model->cate_id]), 'error');
    }
}