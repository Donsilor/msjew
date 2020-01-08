<?php

use yii\grid\GridView;
use common\helpers\Html;
use common\helpers\ImageHelper;

$this->title = '会员信息';
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
<!--                <div class="box-tools">-->
<!--                    --><?//= Html::create(['ajax-edit'], '创建', [
//                        'data-toggle' => 'modal',
//                        'data-target' => '#ajaxModal',
//                    ]) ?>
<!--                </div>-->
            </div>
            <div class="box-body table-responsive">
                <?= GridView::widget([
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
                        ],
                        [
                            'attribute' => 'head_portrait',
                            'value' => function ($model) {
                                return Html::img(ImageHelper::defaultHeaderPortrait(Html::encode($model->head_portrait)),
                                    [
                                        'class' => 'img-circle rf-img-md img-bordered-sm',
                                    ]);
                            },
                            'filter' => false,
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => 'username',
                            'filter' =>  Html::activeTextInput($searchModel, 'username', [
                                'class' => 'form-control',
                                'style' =>'width:100px'
                            ]),
                        ],
//                        'realname',
//                        'mobile',
//                        [
//                            'label' => '账户金额',
//                            'filter' => false, //不显示搜索框
//                            'value' => function ($model) {
//                                return "余额：" . $model->account->user_money . '<br>' .
//                                    "累积金额：" . $model->account->accumulate_money . '<br>' .
//                                    "积分：" . $model->account->user_integral . '<br>' .
//                                    "累计积分：" . $model->account->accumulate_integral;
//                            },
//                            'format' => 'raw',
//                        ],

                        [
                            'label' => '姓/名',
                            'filter' =>  Html::activeTextInput($searchModel, 'lastname', [
                                'class' => 'form-control',
                                'style' =>'width:100px'
                            ]),
                            'value' => function($model){
                                return $model->lastname .'/'. $model->firstname;
                            }
                        ],
                        [
                            'attribute' => 'last_ip',
                            'filter' => false,

                        ],

                        [
                            'label' => '所属国家',
                            'filter' =>  Html::activeTextInput($searchModel, 'country_id', [
                                'class' => 'form-control',
                                'style' =>'width:100px'
                            ]),
                            'value' => 'country.name_zh_cn'
                        ],

                        [
                            'label' => '所属城市',
                            'filter' =>  Html::activeTextInput($searchModel, 'city_id', [
                                'class' => 'form-control',
                                'style' =>'width:100px'
                            ]),
                            'value' => 'city.name_zh_cn'
                        ],

                        [
                            'label'=>'是否购买',
                            'value'=>function($model){
                                $count = \common\models\order\Order::find()->where(['member_id'=>$model->id])->count();
                                return $count > 0 ? "是":"否";
                            },
                            'filter' => Html::activeDropDownList($searchModel, 'is_buy',['1'=>'是','2'=>'否'], [
                                'prompt' => '全部',
                                'class' => 'form-control',
                            ]),
                        ],

                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{ajax-edit} {address} <br/> {edit} {status} {destroy}',
                            'buttons' => [
                                'ajax-edit' => function ($url, $model, $key) {
                                    return Html::linkButton(['ajax-edit', 'id' => $model->id], '账号密码', [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModal',
                                    ]);
                                },
                                'address' => function ($url, $model, $key) {
                                    return Html::linkButton(['address/index', 'member_id' => $model->id], '收货地址');
                                },
                                'recharge' => function ($url, $model, $key) {
                                    return Html::linkButton(['recharge', 'id' => $model->id], '充值', [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModal',
                                    ]);
                                },
                                'edit' => function ($url, $model, $key) {
                                    return Html::edit(['edit', 'id' => $model->id]);
                                },
                                'status' => function ($url, $model, $key) {
                                    return Html::status($model->status);
                                },
                                'destroy' => function ($url, $model, $key) {
                                    return Html::delete(['destroy', 'id' => $model->id]);
                                },
                            ],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>