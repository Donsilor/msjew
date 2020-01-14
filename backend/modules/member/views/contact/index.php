<?php

use common\helpers\Html;
use common\helpers\Url;
use yii\grid\GridView;
use kartik\daterange\DateRangePicker;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('web_seo', '客户留言');
$this->params['breadcrumbs'][] = $this->title;

$start_time = Yii::$app->request->post('start_time', date('Y-m-d', strtotime("-60 day")));
$end_time = Yii::$app->request->post('end_time', date('Y-m-d', strtotime("+1 day")));
$title = Yii::$app->request->post('title');
$addon = <<< HTML
<span class="input-group-addon">
    <i class="glyphicon glyphicon-calendar"></i>
</span>
HTML;
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

            [
                'attribute' => 'id',
                'value' => 'id',
                'filter' =>false,
                'format' => 'raw',
                'headerOptions' => ['width'=>'50'],
            ],


            [
                'attribute'=>'language',
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-md-1'],
                'value' => function ($model){
                    return \Yii::$app->params['languages'][$model->language];
                },
                'filter' => Html::activeDropDownList($searchModel, 'language',\Yii::$app->params['languages'], [
                    'prompt' => '全部',
                    'class' => 'form-control'
                ]),
            ],
            //'member_id',
            [
                'attribute'=>'姓名',
                'filter' => Html::activeTextInput($searchModel, 'first_name', [
                    'class' => 'form-control',
                ]),
                'value'=>function($model){
                    return $model->first_name . $model->last_name;
                },
                'headerOptions' => ['width'=>'100'],
            ],

            [
                'attribute' => 'telphone',
                'value' => 'telphone',
                'filter' => Html::activeTextInput($searchModel, 'telphone', [
                    'class' => 'form-control',
                ]),
                'format' => 'raw',
                'headerOptions' => ['width'=>'120'],
            ],
//            [
//                'attribute' => 'ip',
//                'value' => 'ip',
//                'filter' => false,
//                'format' => 'raw',
//                'headerOptions' => ['width'=>'120'],
//            ],
            [
                'attribute' => 'ip_location',
                'value' => 'ip_location',
                'filter' => Html::activeTextInput($searchModel, 'ip_location', [
                    'class' => 'form-control',
                ]),
                'format' => 'raw',
                'headerOptions' => ['width'=>'120'],
            ],
            [
                'attribute'=>'book_time',
                'filter' => DateRangePicker::widget([    // 日期组件
                    'model' => $searchModel,
                    'attribute' => 'book_time',
                    'value' => $searchModel->created_at,
                    'options' => ['readonly' => true,'class'=>'form-control','style'=>'background-color:#fff;'],
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'locale' => [
                            'separator' => '/',
                        ],
                        'endDate' => date('Y-m-d',time()),
                        'todayHighlight' => true,
                        'autoclose' => true,
                        'todayBtn' => 'linked',
                        'clearBtn' => true,


                    ],

                ]),
                'value'=>'book_time',

            ],
//            [
//                'attribute' => 'created_at',
//                'filter' => DateRangePicker::widget([    // 日期组件
//                    'model' => $searchModel,
//                    'attribute' => 'created_at',
//                    'value' => $searchModel->created_at,
//                    'options' => ['readonly' => true,'class'=>'form-control','style'=>'background-color:#fff;width:190px;'],
//                    'pluginOptions' => [
//                        'format' => 'yyyy-mm-dd',
//                        'locale' => [
//                            'separator' => '/',
//                        ],
//                        'endDate' => date('Y-m-d',time()),
//                        'todayHighlight' => true,
//                        'autoclose' => true,
//                        'todayBtn' => 'linked',
//                        'clearBtn' => true,
//
//
//                    ],
//
//                ]),
//                'value' => function ($model) {
//                    return Yii::$app->formatter->asDate($model->created_at);
//                },
//                'format' => 'raw',
//
//            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-md-1'],
                'value' => function ($model){
                    return \common\enums\FollowStatusEnum::getValue($model->status??0);
                },
                'filter' => Html::activeDropDownList($searchModel, 'status',\common\enums\FollowStatusEnum::getMap(), [
                    'prompt' => '全部',
                    'class' => 'form-control',
                ]),
            ],
            [
                'attribute'=>'type_id',
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-md-1'],
                'value' => function ($model){
                    return \common\enums\ContactEnum::getMap()[$model->type_id];
                },
                'filter' => Html::activeDropDownList($searchModel, 'type_id',\common\enums\ContactEnum::getMap(), [
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
                'template' => '{status} {info}',
                'buttons' => [
                'edit' => function($url, $model, $key){
                        return Html::edit(['edit', 'id' => $model->id]);
                },
                'info' => function($url, $model, $key){
                    return Html::a('查看',['info', 'id' => $model->id],['class'=>'btn btn-info btn-sm']);
                },
               'status' => function($url, $model, $key){
                        return Html::status($model['status'],[],\common\enums\FollowStatusEnum::getMap());
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
