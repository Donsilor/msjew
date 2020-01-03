<?php

use common\helpers\Url;
use common\helpers\Html;
use yii\grid\GridView;
use common\enums\OrderStatusEnum;
use kartik\daterange\DateRangePicker;

$this->title = '订单列表';
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li<?php if (Yii::$app->request->get('order_status', -1) == -1) echo ' class="active"' ?>><a
                            href="<?= Url::to(['order/index']) ?>"> 全部</a></li>
                <? foreach ($orderStatus as $statusValue => $statusName) { ?>
                    <li<?php if (Yii::$app->request->get('order_status', -1) == $statusValue) echo ' class="active"' ?>>
                        <a href="<?= Url::to(['order/index', 'order_status' => $statusValue]) ?>"><?= $statusName ?></a>
                    </li>
                <? } ?>
            </ul>

            <div class="tab-content">
                <div class="box-body table-responsive top-form">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="col-sm-3">
                                <div class="input-group m-b">
                                    邮箱：<br/>
                                    <?= Html::activeTextInput($searchModel, 'member.email', [
                                        'class' => 'form-control',
                                        'style' => 'width:200px',
                                    ]);
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group m-b">
                                    支付方式：<br/>
                                    <?= Html::activeDropDownList($searchModel, 'payment_type', \common\enums\PayEnum::getMap(), [
                                        'prompt' => '全部',
                                        'class' => 'form-control',
                                    ]);
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group m-b">
                                    下单时间：<br/>
                                    <?= DateRangePicker::widget([    // 日期组件
                                        'model' => $searchModel,
                                        'attribute' => 'created_at',
                                        'value' => '',
                                        'options' => ['readonly' => true],
                                        'pluginOptions' => [
                                            'format' => 'yyyy-mm-dd',
                                            'locale' => [
                                                'separator' => '/',
                                            ],
                                            'endDate' => date('Y-m-d', time()),
                                            'todayHighlight' => true,
                                            'autoclose' => true,
                                            'todayBtn' => 'linked',
                                            'clearBtn' => true,
                                        ],
                                    ])
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="col-sm-3">
                                <div class="input-group m-b">
                                    语言版本：<br/>
                                    <?= Html::activeDropDownList($searchModel, 'language', \common\enums\LanguageEnum::getMap(), [
                                        'prompt' => '全部',
                                        'class' => 'form-control',
                                    ]);
                                    ?>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group m-b">
                                    来源客户端：<br/>
                                    <?= Html::activeDropDownList($searchModel, 'order_from', \common\enums\AppEnum::getMap(), [
                                        'prompt' => '全部',
                                        'class' => 'form-control',
                                    ]);
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                                'visible' => false, // 不显示#
                            ],
                            [
                                'attribute' => 'id',
                                'headerOptions' => ['class' => 'col-md-1'],
                                'filter' =>
                                    Html::activeTextInput($searchModel, 'member.email', [
                                        'class' => 'hidden',
                                    ]) .
                                    Html::activeTextInput($searchModel, 'payment_type', [
                                        'class' => 'hidden',
                                    ]) .
                                    Html::activeTextInput($searchModel, 'created_at', [
                                        'class' => 'hidden',
                                    ]) .
                                    Html::activeTextInput($searchModel, 'language', [
                                        'class' => 'hidden',
                                    ]) .
                                    Html::activeTextInput($searchModel, 'order_from', [
                                        'class' => 'hidden',
                                    ])
                            ],
                            [
                                'label' => '订单号',
                                'filter' => Html::activeTextInput($searchModel, 'order_sn', [
                                    'class' => 'form-control',
                                    'style' => 'width:200px'
                                ]),
                                'value' => function ($model) {
                                    return $model->order_sn;
                                },
                                'format' => 'raw',
                            ],
                            [
                                'label' => '客户姓名',
                                'filter' => Html::activeTextInput($searchModel, 'member.realname', [
                                    'class' => 'form-control',
                                    'style' => 'width:200px'
                                ]),
                                'value' => function ($model) {
                                    return $model->member['realname'];
                                },
                                'format' => 'raw',
                            ],
                            [
                                'label' => '联系方式',
                                'filter' => Html::activeTextInput($searchModel, 'member.mobile', [
                                    'class' => 'form-control',
                                    'style' => 'width:200px'
                                ]),
                                'value' => function ($model) {
                                    return $model->member['mobile'];
                                },
                                'format' => 'raw',
                            ],
                            [
                                'label' => '账号',
                                'filter' => Html::activeTextInput($searchModel, 'member.username', [
                                    'class' => 'form-control',
                                    'style' => 'width:200px'
                                ]),
                                'value' => function ($model) {
                                    return $model->member['username'];
                                },
                                'format' => 'raw',
                            ],
                            [
                                'label' => '订单金额',
                                'filter' => true, //不显示搜索框
                                'value' => function ($model) {
                                    return $model->account['order_amount'];
                                },
                                'format' => 'raw',
                            ],
                            [
                                'label' => '所属区域',
                                'filter' => true, //不显示搜索框
                                'value' => function ($model) {
                                    return "所属区域";
                                },
                                'format' => 'raw',
                            ],
                            [
                                'label' => '所属国家',
                                'filter' => true, //不显示搜索框
                                'value' => function ($model) {
                                    return "所属国家";
                                },
                                'format' => 'raw',
                            ],
                            [
                                'label' => '支付状态',
                                'filter' => Html::activeDropDownList($searchModel, 'order_sn', ['1' => '是', '2' => '否'], [
                                    'prompt' => '全部',
                                    'class' => 'form-control',
                                ]),
                                'value' => function ($model) {
                                    return "支付状态";
                                },
                                'format' => 'raw',
                            ],
                            [
                                'label' => '订单状态',
                                'filter' => Html::activeDropDownList($searchModel, 'order_status', $orderStatus, [
                                    'prompt' => '全部',
                                    'class' => 'form-control',
                                ]),
                                'value' => function ($model) {
                                    return OrderStatusEnum::getValue($model->order_status);
                                },
                                'format' => 'raw',
                            ],
                            [
                                'label' => '跟进人',
                                'filter' => true, //不显示搜索框
                                'value' => function ($model) {
                                    return "跟进人";
                                },
                                'format' => 'raw',
                            ],
                            [
                                'label' => '跟进状态',
                                'filter' => Html::activeDropDownList($searchModel, 'order_sn', ['1' => '是', '2' => '否'], [
                                    'prompt' => '全部',
                                    'class' => 'form-control',
                                ]),
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
                                    'view' => function ($url, $model, $key) {
                                        return Html::a('预览', ['view', 'id' => $model->id], ['class' => 'btn btn-info btn-sm']);
                                    },
                                    'view2' => function ($url, $model, $key) {
                                        return Html::a('跟进', ['view', 'id' => $model->id], ['class' => 'btn btn-info btn-sm']);
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

<script>
    (function ($) {
        $(".top-form input,select").change(function () {
            $(".filters input[name='"+$(this).attr('name')+"']").val($(this).val()).trigger('change');
        });
    })(window.jQuery);
</script>