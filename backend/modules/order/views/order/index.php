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
                                'attribute' => 'order_sn',
                                'filter' => Html::activeTextInput($searchModel, 'order_sn', [
                                    'class' => 'form-control',
                                ]),
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'member.realname',
                                'filter' => Html::activeTextInput($searchModel, 'member.realname', [
                                    'class' => 'form-control',
                                ]),
                                'value' => function ($model) {
                                    return $model->member['realname'];
                                },
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'member.mobile',
                                'filter' => Html::activeTextInput($searchModel, 'member.mobile', [
                                    'class' => 'form-control',
                                ]),
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'member.username',
                                'filter' => Html::activeTextInput($searchModel, 'member.username', [
                                    'class' => 'form-control',
                                ]),
//                                'value' => function ($model) {
//                                    return $model->member['username'];
//                                },
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'account.order_amount',
                                'filter' => true, //不显示搜索框
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'address.country_name',
                                'headerOptions' => ['class' => 'col-md-1'],
                                'filter' => Html::activeDropDownList($searchModel, 'address.country_id', \Yii::$app->services->area->getDropDown(0), [
                                            'prompt' => '全部',
                                            'class' => 'form-control',
                                ]),
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'address.city_name',
                                'filter' => true, //不显示搜索框
                                'format' => 'raw',
                            ],
                            [
                                'label' => '支付状态',
                                'filter' => Html::activeDropDownList($searchModel, 'api_pay_time', ['1' => '是', '2' => '否'], [
                                    'prompt' => '全部',
                                    'class' => 'form-control',
                                ]),
                                'value' => function ($model) {
                                    return "支付状态";
                                },
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'order_status',
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
                                'filter' => Html::activeTextInput($searchModel, 'follower.realname', [
                                    'class' => 'form-control',
                                    'style' => 'width:200px'
                                ]),
                                'value' => function ($model) {
                                    return $model->follower->realname;
                                },
                                'format' => 'raw',
                            ],
                            [
                                'label' => '跟进状态',
                                'filter' => Html::activeDropDownList($searchModel, 'api_pay_time', ['1' => '是', '2' => '否'], [
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