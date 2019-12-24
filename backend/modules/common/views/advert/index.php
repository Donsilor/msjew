<?php

use common\helpers\Html;
use common\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('advert', '广告位');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li><a href="<?= Url::to(['advert-images/index']) ?>"> 广告位图片</a></li>
                <li class="active"><a href="<?= Url::to(['advert/index']) ?>"> 广告位位置</a></li>
                <li class="pull-right">
                    <?= Html::create(['ajax-edit-lang'], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]) ?>
                </li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane">
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
                'attribute'=>'lang.adv_name',
                'value' =>'lang.adv_name',
                'filter' => Html::activeTextInput($searchModel, 'adv_name', [
                    'class' => 'form-control'
                ]),
            ],
            [
                'attribute'=>'adv_type',
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-md-1'],
                'value' => function ($model){
                    return \common\enums\SettingEnum::$advTypeAction[$model->adv_type];
                },
                'filter' => Html::activeDropDownList($searchModel, 'adv_type',\common\enums\SettingEnum::$advTypeAction, [
                    'prompt' => '全部',
                    'class' => 'form-control'
                ]),
            ],


            [
                'attribute'=>'尺寸',
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-md-1'],
                'value' => function ($model){
                    return $model->adv_width ."*".$model->adv_height;
                }
            ],
            //'adv_width',

            [
                'attribute'=>'show_type',
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-md-1'],
                'value' => function ($model){
                    return \common\enums\SettingEnum::$showTypeActionSimple[$model->show_type];
                },
                'filter' => Html::activeDropDownList($searchModel, 'show_type',\common\enums\SettingEnum::$showTypeActionSimple, [
                    'prompt' => '全部',
                    'class' => 'form-control'
                ]),
            ],
            //'open_type',
            //'remark',
            //'status',
            //'created_at',
            //'updated_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{edit}  {status}',
                'buttons' => [
                    'edit' => function ($url, $model, $key) {
                        return Html::edit(['ajax-edit-lang','id' => $model->id], '编辑',
                            [
                                'data-toggle' => 'modal',
                                'data-target' => '#ajaxModalLg',
                            ]);
                    },
                    'detail'=>function($url, $model, $key){
                        return Html::linkButton(['advert-images/index', 'adv_id' => $model->id], '图片');

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
</div>
