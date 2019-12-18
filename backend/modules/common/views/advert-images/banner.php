<?php

use common\helpers\Html;
use common\helpers\Url;
use yii\grid\GridView;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li><a href="<?= Url::to(['advert-images/index']) ?>"> 广告位图片</a></li>
                <li class="active"><a href="<?= Url::to(['advert-images/banner']) ?>">banner图片</a></li>
                <li><a href="<?= Url::to(['advert/index']) ?>"> 广告位位置</a></li>
                <li class="pull-right">
                    <?= Html::create(['banner-edit-lang'], '创建', [
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
        'layout'=> '{items}',
        'showFooter' => true,//显示footer行
        'id'=>'grid',
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'visible' => false,
            ],

            'id',
            [
                'attribute'=>'title',
                'value' =>'lang.title',
                'filter' => Html::activeTextInput($searchModel, 'title', [
                    'class' => 'form-control',
                    'style' =>'width:100px'
                ]),
            ],

            [
                'label' => '图片',
                "format"=>'raw',
                'value' => function($model) {
                    return Html::img($model->lang->adv_image,["width"=>"100",]);
                 },
             ],


            [
                'label' => '有效期',
                'format'=>'raw',
                'value' => function ($model) {
                     $str = "开始时间：".date('Y-m-d', strtotime($model->start_time));
                     $str .= "<br/>";
                     $str .= "结束时间：".date('Y-m-d', strtotime($model->end_time));
                    return $str;
                },

//                 'filter' => \kartik\daterange\DateRangePicker::widget([// 日期组件
//                 'model' => $searchModel,
//                 'attribute' => 'start_end',
//                 'value' => $searchModel->start_time,
//                 'convertFormat' => true,
//                 'pluginOptions' => [
//                 'language' => 'zn-ch',
//                 'locale' => [
//                 'format' => 'Y-m-d H:i:s',
//                 'applyLabel' => '确定', // 确定文字的显示
//                 'cancelLabel' => '取消',// 取消文字的显示
//                'fromLabel' => '开始',// 开始文字的显示
//                'toLabel' => '结束',// 结束文字的显示
//                'monthNames' => [ // 月份的中文显示
//                '一月', '二月', '三月', '四月', '五月', '六月','七月', '八月', '九月', '十月', '十一月', '十二月',
//                ],
//                 'daysOfWeek' => ['日', '一', '二', '三', '四', '五', '六' ],
//                    'separator' => '/'// 时间间隔符 设置为 /  例: 2016-12-11 12:00:00/2016-12-12 12:00:00
//                 ]
//                ]
//                 ])
            ],

            [
                'label'=>'当前状态',
                'format'=>'html',
                'value'=>function($model){
                    return Html::timeStatus(strtotime($model->start_time), strtotime($model->end_time));
                }
            ],
           // 'adv_url:url',
//            'start_time:date',
//            'end_time:date',
//            'updated_at:date',
            [
                'attribute' => 'adv_id',
                'value' => 'types.lang.type_name',
                'filter' => Html::activeDropDownList($searchModel, 'adv_id', $type, [
                        'prompt' => '全部',
                        'class' => 'form-control'
                    ]
                )
            ],
            [
                'attribute' => 'sort',
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-md-1'],
                'value' => function ($model, $key, $index, $column){
                    return  Html::sort($model->sort);
                }
            ],
//            [
//                'attribute' => 'status',
//                'format' => 'raw',
//                'headerOptions' => ['class' => 'col-md-1'],
//                'value' => function ($model){
//                    return \common\enums\StatusEnum::getValue($model->status);
//                },
//                'filter' => Html::activeDropDownList($searchModel, 'status',\common\enums\StatusEnum::getMap(), [
//                    'prompt' => '全部',
//                    'class' => 'form-control',
//
//                ]),
//            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{edit} {status} {delete}',
                'buttons' => [
                'edit' => function($url, $model, $key){
                        return Html::edit(['banner-edit-lang', 'id' => $model->id , 'adv_id' => $model->adv_id],'编辑',[
                            'data-toggle' => 'modal',
                            'data-target' => '#ajaxModalLg',
                        ]);
                },
                'status' => function($url, $model, $key){
                        return Html::status($model['status']);
                },

                'delete' => function($url, $model, $key){
                        return Html::delete(['delete', 'id' => $model->id]);
                }
                ]
            ]
    ]
    ]); ?>

            </div>
        </div>
    </div>
</div>
