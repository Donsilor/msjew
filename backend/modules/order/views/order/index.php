<?php

use common\helpers\Url;
use common\helpers\Html;
use yii\grid\GridView;

$this->title = '订单列表';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li<?php if(Yii::$app->request->get('order_status', -1)==-1) echo ' class="active"' ?>><a href="<?= Url::to(['order/index']) ?>"> 全部</a></li>
                <? foreach($orderStatus as $statusValue => $statusName) { ?>
                    <li<?php if(Yii::$app->request->get('order_status',-1)==$statusValue) echo ' class="active"' ?>><a href="<?= Url::to(['order/index','order_status'=>$statusValue]) ?>"><?= $statusName ?></a></li>
                <? } ?>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane">
                    <?php
                    $config = [
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        //重新定义分页样式
                        'tableOptions' => ['class' => 'table table-hover'],
                        'columns' => [
//                            [
//                                'class' => 'yii\grid\SerialColumn',
//                            ],
                            [
                                'label' => 'ID',
                                'filter' => false, //不显示搜索框
                                'value' => function ($model) {
                                    return "ID";
                                },
                                'format' => 'raw',
                            ],
                            [
                                'label' => '订单号',
                                'filter' => false, //不显示搜索框
                                'value' => function ($model) {
                                    return "客户姓名";
                                },
                                'format' => 'raw',
                            ],
                            [
                                'label' => '客户姓名',
                                'filter' => false, //不显示搜索框
                                'value' => function ($model) {
                                    return "客户姓名";
                                },
                                'format' => 'raw',
                            ],
                            [
                                'label' => '联系方式',
                                'filter' => false, //不显示搜索框
                                'value' => function ($model) {
                                    return "联系方式";
                                },
                                'format' => 'raw',
                            ],
                            [
                                'label' => '账号',
                                'filter' => false, //不显示搜索框
                                'value' => function ($model) {
                                    return "账号";
                                },
                                'format' => 'raw',
                            ],
                            [
                                'label' => '订单金额',
                                'filter' => false, //不显示搜索框
                                'value' => function ($model) {
                                    return "订单金额";
                                },
                                'format' => 'raw',
                            ],
                            [
                                'label' => '所属区域',
                                'filter' => false, //不显示搜索框
                                'value' => function ($model) {
                                    return "所属区域";
                                },
                                'format' => 'raw',
                            ],
                            [
                                'label' => '所属国家',
                                'filter' => false, //不显示搜索框
                                'value' => function ($model) {
                                    return "所属国家";
                                },
                                'format' => 'raw',
                            ],
                            [
                                'label' => '支付状态',
                                'filter' => false, //不显示搜索框
                                'value' => function ($model) {
                                    return "支付状态";
                                },
                                'format' => 'raw',
                            ],
                            [
                                'label' => '订单状态',
                                'filter' => false, //不显示搜索框
                                'value' => function ($model) {
                                    return "订单状态";
                                },
                                'format' => 'raw',
                            ],
                            [
                                'label' => '跟进人',
                                'filter' => false, //不显示搜索框
                                'value' => function ($model) {
                                    return "跟进人";
                                },
                                'format' => 'raw',
                            ],
                            [
                                'label' => '跟进状态',
                                'filter' => false, //不显示搜索框
                                'value' => function ($model) {
                                    return "跟进状态";
                                },
                                'format' => 'raw',
                            ],
                            [
                                'header' => "操作",
                                'class' => 'yii\grid\ActionColumn',
                                'template' => ' {view} {view2}',
                                'buttons' => [
                                    'view'=> function($url, $model, $key) {
                                        return Html::a('预览', ['view', 'id'=>$model->id], ['class'=>'btn btn-info btn-sm']);
                                    },
                                    'view2'=> function($url, $model, $key) {
                                        return Html::a('跟进', ['view', 'id'=>$model->id], ['class'=>'btn btn-info btn-sm']);
                                    },
                                ],
                            ],
                        ],
                    ];

                    echo GridView::widget($config);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>