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
                            [
                                'class' => 'yii\grid\SerialColumn',
                            ],
                            [
                                'label' => '单号',
                                'filter' => false, //不显示搜索框
                                'value' => function ($model) {
                                    return "订单号：" . $model->order_sn . '<br>' .
                                        "支付单号：" . $model->pay_sn . '<br>' .
                                        "物流单号：" . $model->express_no;
                                },
                                'format' => 'raw',
                            ],
                            [
                                'label' => '商品明细',
                                'filter' => false, //不显示搜索框
                                'value' => function ($model) {
                                    return "订单号：" . $model->order_sn . '<br>' .
                                        "物流单号：" . $model->express_no;
                                },
                                'format' => 'raw',
                            ],
                            [
                                'label' => '备注',
                                'filter' => false, //不显示搜索框
                                'value' => function ($model) {
                                    return "买家：" . $model->buyer_remark . '<br>' .
                                        "商家：" . $model->seller_remark;
                                },
                                'format' => 'raw',
                            ],
                            [
                                'label' => '时间',
                                'filter' => false, //不显示搜索框
                                'value' => function ($model) {
                                    return "创建：" . Yii::$app->formatter->asDatetime($model->created_at) . '<br>' .
                                        "支付：" . Yii::$app->formatter->asDatetime($model->payment_time). '<br>' .
                                        "完成：" . Yii::$app->formatter->asDatetime($model->finished_time);
                                },
                                'format' => 'raw',
                            ],
                            [
                                'header' => "操作",
                                'class' => 'yii\grid\ActionColumn',
                                'template' => ' {view}',
                                'buttons' => [
                                    'view'=> function($url, $model, $key) {
                                        return Html::a('预览', ['view', 'id'=>$model->id], ['class'=>'btn btn-info btn-sm']);
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