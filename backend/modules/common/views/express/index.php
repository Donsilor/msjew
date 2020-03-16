<?php

use common\helpers\Html;
use common\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('express', '快递配置');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                <div class="box-tools">
                    <?= Html::create(['ajax-edit-lang'], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ])?>
                </div>
            </div>
            <div class="box-body table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-hover'],
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'visible' => false,
            ],


            [
                'headerOptions' => ['width'=>'80'],
                'attribute' => 'id',
                'filter' => false,
            ],
            [
                'attribute' => 'cover',
                "format"=>'raw',
                'filter' => false,
                'value' => function($model) {
                    return \common\helpers\ImageHelper::fancyBox($model->cover,120,'auto');
                },
            ],
            [
                //'headerOptions' => ['width'=>'200'],
                'attribute' => 'lang.express_name',
                'value' => 'lang.express_name',
                'filter' => Html::activeTextInput($searchModel, 'express_name', [
                    'class' => 'form-control',
                ]),
                'format' => 'raw',
            ],



            [
                'headerOptions' => ['width'=>'80'],
                'attribute' => 'code',
                'value' => 'code',
                'filter' => Html::activeTextInput($searchModel, 'code', [
                    'class' => 'form-control',
                ]),
                'format' => 'raw',
            ],
            [
                'attribute' => 'sort',
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-md-1'],
                'value' => function ($model, $key, $index, $column){
                    return  Html::sort($model->sort);
                }
            ],
//            'created_at:date',
            [
                'headerOptions' => ['width'=>'120'],
                'attribute' => 'updated_at',
                'value' => function ($model, $key, $index, $column){
                    return date('Y-m-d',$model->updated_at);
                },
                'filter' => false,
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{edit} {status} {delete}',
                'buttons' => [
                'edit' => function($url, $model, $key){
                    return Html::edit(['ajax-edit-lang','id' => $model->id], '编辑', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]);
                },
               'status' => function($url, $model, $key){
                        return Html::status($model['status']);
                  },
                'delete' => function($url, $model, $key){
                        return Html::delete(['delete', 'id' => $model->id]);
                },
                ]
            ]
    ]
    ]); ?>
            </div>
        </div>
    </div>
</div>
