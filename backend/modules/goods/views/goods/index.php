<?php

use common\helpers\Html;
use common\helpers\Url;
use yii\grid\GridView;
use common\helpers\ImageHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('goods', $typeModel['type_name'].'商品列表');
$style_title = Yii::t('goods', $typeModel['type_name'].'管理');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">

            <ul class="nav nav-tabs">
                <li><a href="<?= Url::to(['style/index?type_id='.Yii::$app->request->get('type_id',0)]) ?>"> <?= Html::encode($style_title) ?></a></li>
                <li class="active"><a href="<?= Url::to(['goods/index?type_id='.Yii::$app->request->get('type_id',0)]) ?>"> <?= Html::encode($this->title) ?></a></li>
                <li class="pull-right">
                    <div class="box-header box-tools"> <?= Html::create(['edit-lang','type_id'=>Yii::$app->request->get('type_id',0)]) ?></div>
                </li>
            </ul>
            <div class="box-body table-responsive">
    <?php echo Html::batchButtons(false)?>         
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-hover'],
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'visible' => false,
            ],

            'id',
            //'style_id',
            [
                'attribute' => 'goods_image',
                'value' => function ($model) {
                    return ImageHelper::fancyBox($model->goods_image);
                },
                'filter' => false,
                'format' => 'raw',
                'headerOptions' => ['width'=>'80'],
            ],
            [
                'attribute'=>'styleLang.style_name',
                'filter' => false,
                'value' => function ($model) {
                    return $model->styleLang['style_name'];
                },
                'headerOptions' => ['width'=>'100'],
            ],

            'goods_sn',
            [
                'attribute'=>'style.style_sn',
                'filter' => false,
                'value' => function ($model) {
                    return $model->style['style_sn'];
                },
                'headerOptions' => ['width'=>'100'],
            ],
            'sale_price',
            [
                'attribute'=>'markup.sale_price',
                'filter' => false,
                'value' => function ($model) {
                    return $model->markup['sale_price'];
                },
                'headerOptions' => ['width'=>'100'],
            ],
            'status',

            'created_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{status} {view}',
                'buttons' => [
                'edit' => function($url, $model, $key){
                        return Html::edit(['edit', 'id' => $model->id,'returnUrl' => Url::getReturnUrl()]);
                },
               'status' => function($url, $model, $key){
                        return Html::status($model['status']);
                  },
                'delete' => function($url, $model, $key){
                        return Html::delete(['delete', 'id' => $model->id]);
                },
                'view'=> function($url, $model, $key){
                    return Html::a('预览', '',['class'=>'btn btn-info btn-sm']);
                },
                ]
            ]
    ]
    ]); ?>
            </div>
        </div>
    </div>
</div>
