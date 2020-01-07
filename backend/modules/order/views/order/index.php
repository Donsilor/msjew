<?php

use common\helpers\Url;
use common\helpers\Html;
use yii\grid\GridView;
use common\enums\OrderStatusEnum;
use kartik\daterange\DateRangePicker;

$this->title = Yii::t('order', '订单');
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li<?php if (Yii::$app->request->get('order_status', -1) == -1) echo ' class="active"' ?>><a href="<?= Url::to(['order/index']) ?>"> 全部</a></li>
                <?php foreach (common\enums\OrderStatusEnum::getMap() as $statusValue => $statusName) { ?>
                    <li<?php if (Yii::$app->request->get('order_status', -1) == $statusValue) echo ' class="active"' ?>>
                        <a href="<?= Url::to(['order/index', 'order_status' => $statusValue]) ?>"><?= $statusName ?></a>
                    </li>
                <?php } ?>
            </ul>

            <div class="tab-content">
                <div class="box-body top-form">
                    <div class="row col-sm-12">
                        <div class="col-sm-3">
                            <?= $searchModel->model->getAttributeLabel('member.email') ?>：<br/>
                            <?= Html::activeTextInput($searchModel, 'member.email', [
                                'class' => 'form-control',
                            ]);
                            ?>
                        </div>
                        <div class="col-sm-3">
                            <?= $searchModel->model->getAttributeLabel('payment_type') ?>：<br/>
                            <?= Html::activeDropDownList($searchModel, 'payment_type', \common\enums\PayEnum::getMap(), [
                                'prompt' => '全部',
                                'class' => 'form-control',
                            ]);
                            ?>
                        </div>
                        <div class="col-sm-3">
                            <?= $searchModel->model->getAttributeLabel('created_at') ?>：<br/>
                            <?= DateRangePicker::widget([    // 日期组件
                                'model' => $searchModel,
                                'attribute' => 'created_at',
                                'value' => '',
                                'options' => ['readonly' => true, 'class' => 'form-control',],
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
                        <div class="col-sm-3">
                            <?= $searchModel->model->getAttributeLabel('order_from') ?>：<br/>
                            <?= Html::activeDropDownList($searchModel, 'order_from', \common\enums\AppEnum::getMap(), [
                                'prompt' => '全部',
                                'class' => 'form-control',
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row col-sm-12">
                        <?= Html::batchAudit('ajax-batch-audit', '批量审核', ['data-grid'=>'grid']) ?>
                    </div>
                </div>
                <div class="active tab-pane">
                    <?= GridView::widget([
                        'id'=>'grid',
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        //重新定义分页样式
                        'tableOptions' => ['class' => 'table table-hover'],
                        'columns' => [
//                            [
//                                'class' => 'yii\grid\SerialColumn',
//                                'visible' => false, // 不显示#
//                            ],
                            [
                                'class'=>'yii\grid\CheckboxColumn',
                                'name'=>'id',  //设置每行数据的复选框属性
                                'headerOptions' => ['width'=>'30'],
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
                                'filter' => Html::activeTextInput($searchModel, 'account.order_amount', [
                                        'class' => 'form-control',
                                ]),
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'address.country_id',
                                'headerOptions' => ['class' => 'col-md-1'],
                                'filter' => Html::activeDropDownList($searchModel, 'address.country_id', \Yii::$app->services->area->getDropDown(0), [
                                    'prompt' => '全部',
                                    'class' => 'form-control',
                                ]),
                                'value' => function ($model) {
                                    return \Yii::$app->services->area->getAreaName($model->address->country_id);
                                },
                                'format' => 'raw',
                            ],
                            [
                                'label' => '支付状态',
                                 'headerOptions' => ['class' => 'col-md-1'],
                                'filter' => Html::activeDropDownList($searchModel, 'payment_status', common\enums\PayStatusEnum::getMap(), [
                                    'prompt' => '全部',
                                    'class' => 'form-control',
                                ]), 
                                'value' => function ($model) {
                                     return common\enums\PayStatusEnum::getValue($model->payment_status);
                                },
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'order_status',
                                 'headerOptions' => ['class' => 'col-md-1'],
                                 'filter' => Html::activeDropDownList($searchModel, 'order_status', common\enums\OrderStatusEnum::getMap(), [
                                    'prompt' => '全部',
                                    'class' => 'form-control',
                                ]),
                                'value' => function ($model) {
                                    return common\enums\OrderStatusEnum::getValue($model->order_status);
                                },
                                'format' => 'raw',
                            ],
                            
                            [
                                'label' => '跟进人',
                                'filter' => Html::activeTextInput($searchModel, 'follower.username', [
                                    'class' => 'form-control',
                                ]),
                                'value' => function ($model) {
                                    return $model->follower ? $model->follower->username : null;
                                },
                                'format' => 'raw',
                            ],
                            [
                                'label' => '跟进状态',
                                'headerOptions' => ['class' => 'col-md-1'],
                                'filter' => Html::activeDropDownList($searchModel, 'followed_status',common\enums\FollowStatusEnum::getMap(), [
                                        'prompt' => '全部',
                                        'class' => 'form-control',
                                ]),
                                'value' => function ($model) {
                                     return common\enums\FollowStatusEnum::getValue($model->followed_status);
                                },
                                'format' => 'raw',
                            ],
                            [
                                'header' => "操作",
                                'class' => 'yii\grid\ActionColumn',
                                'template' => ' {view} {follower} {audit} {delivery}',
                                'buttons' => [
                                    'view' => function ($url, $model, $key) {
                                        return Html::a('详情', ['view', 'id' => $model->id], ['class' => 'btn btn-info btn-sm']);
                                    },
                                    'follower' => function ($url, $model, $key) {
                                        return Html::edit(['edit-follower', 'id' => $model->id], '跟进', [
                                            'data-toggle' => 'modal',
                                            'data-target' => '#ajaxModal',
                                        ]);
                                    },
                                    'audit' => function ($url, $model, $key) {
                                        return Html::batchAudit();
                                    },
                                    'delivery' => function($url, $model, $key) {
                                        return Html::edit(['edit-delivery', 'id' => $model->id], '发货', [
                                            'data-toggle' => 'modal',
                                            'data-target' => '#ajaxModal',
                                        ]);
                                    }
                                ],
                            ],
                        ],
                    ]);
                  ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function audit(id) {
        let _id = [];
        if(id===undefined) {
            _id= []
        }
        else {
            _id.push(id)
        }
    }

    (function ($) {
        /**
         * 头部文本框触发列表过滤事件
         */
        $(".top-form input,select").change(function () {
            $(".filters input[name='" + $(this).attr('name') + "']").val($(this).val()).trigger('change');
        });


    })(window.jQuery);
</script>