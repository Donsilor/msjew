<?php

use common\helpers\Html;
use common\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('web_seo', '客户留言');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                <div class="box-tools">
<!--                    --><?//= Html::create(['edit']) ?>
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

            'id',


            [
                'attribute'=>'language',
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-md-1'],
                'value' => function ($model){
                    return \common\enums\LanguageEnum::getMap()[$model->language];
                },
                'filter' => Html::activeDropDownList($searchModel, 'language',\common\enums\LanguageEnum::getMap(), [
                    'prompt' => '全部',
                    'class' => 'form-control'
                ]),
            ],
            //'member_id',
            'first_name',
            'last_name',
            'telphone',

            [
                'attribute'=>'type_id',
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-md-1'],
                'value' => function ($model){
                    return \common\enums\ContactTypeEnum::getMap()[$model->type_id];
                },
                'filter' => Html::activeDropDownList($searchModel, 'type_id',\common\enums\ContactTypeEnum::getMap(), [
                    'prompt' => '全部',
                    'class' => 'form-control'
                ]),
            ],
            //'content:ntext',
            //'status',
            //'created_at',
            //'updated_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{info}',
                'buttons' => [
                'edit' => function($url, $model, $key){
                        return Html::edit(['edit', 'id' => $model->id]);
                },
                'info' => function($url, $model, $key){
                    return Html::a('查看',['info', 'id' => $model->id]);
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
