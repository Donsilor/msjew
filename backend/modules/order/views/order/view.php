<?php

use common\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\langbox\LangBox;
use yii\base\Widget;
use common\widgets\skutable\SkuTable;
use common\helpers\Url;
use common\helpers\ArrayHelper;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\order\order */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('order', 'view');
$this->params['breadcrumbs'][] = ['label' => Yii::t('order', 'view'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
//
?>
<?php $form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['ajax-edit', 'id' => $model['id']]),
    'fieldConfig' => [
        'template' => "<div class='col-sm-3 text-right'>{label}</div><div class='col-sm-9'>{input}\n{hint}\n{error}</div>",
    ]
]); ?>

    <div class="box-body nav-tabs-custom">
        <h2 class="page-header">订单详情信息--详情页</h2>
        <?php $tab_list = [0 => '全部', 1 => '基础信息', 2 => '商品明细', 3 => '图文信息', 4 => 'SEO优化']; ?>
        <div class="tab-content">
            <div class="row nav-tabs-custom tab-pane tab0 active" id="tab_1">
                <ul class="nav nav-tabs pull-right">
                    <li class="pull-left header"><i class="fa fa-th"></i> 订单信息</li>
                </ul>
                <div class="box-body" style="margin-left:9px">
                    <div class="row">
                        <div class="col-lg-1 text-right"><label>订单号：</label></div>
                        <div class="col-lg-2"><?= $model->order_sn ?></div>
                        <div class="col-lg-1 text-right"><label>语言版本：</label></div>
                        <div class="col-lg-2"><?= \common\enums\LanguageEnum::getValue($model->language) ?></div>
                        <div class="col-lg-1 text-right"><label>支付状态：</label></div>
                        <div class="col-lg-2"><?= $model->order_sn ?></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-1 text-right"><label>客户姓名：</label></div>
                        <div class="col-lg-2"><?= $model->member->realname ?></div>
                        <div class="col-lg-1 text-right"><label>所属区域：</label></div>
                        <div class="col-lg-2"><?= $model->order_sn ?></div>
                        <div class="col-lg-1 text-right"><label>订单状态：</label></div>
                        <div class="col-lg-2"><?= \common\enums\OrderStatusEnum::getValue($model->order_status) ?></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-1 text-right"><label>联系方式：</label></div>
                        <div class="col-lg-2"><?= $model->member->mobile ?></div>
                        <div class="col-lg-1 text-right"><label>支付方式：</label></div>
                        <div class="col-lg-2"><?= \common\enums\PayEnum::getValue($model->payment_type) ?></div>
                        <div class="col-lg-1 text-right"><label>跟进状态：</label></div>
                        <div class="col-lg-2"><?= $model->order_sn ?></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-1 text-right"><label>账号：</label></div>
                        <div class="col-lg-2"><?= $model->member->username ?></div>
                        <div class="col-lg-1 text-right"><label>下单时间：</label></div>
                        <div class="col-lg-2"><?= Yii::$app->formatter->asDatetime($model->created_at, 'Y-M-D H:i:s') ?></div>
                        <div class="col-lg-1 text-right"><label>审核状态：</label></div>
                        <div class="col-lg-2"><?= $model->order_sn ?></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-1 text-right"><label>所属国家：</label></div>
                        <div class="col-lg-2"><?= $model->address->country_name ?></div>
                        <div class="col-lg-1 text-right"><label>客户端：</label></div>
                        <div class="col-lg-2"><?= \common\enums\AppEnum::getValue($model->order_from) ?></div>
                        <div class="col-lg-1 text-right"><label>跟进人：</label></div>
                        <div class="col-lg-2"><?= $model->follower->realname ?></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-1 text-right"><label>所属城市：</label></div>
                        <div class="col-lg-2"><?= $model->address->country_name ?></div>
                        <div class="col-lg-1 text-right"><label>IP：</label></div>
                        <div class="col-lg-2"><?= $model->order_sn ?></div>
                        <div class="col-lg-1 text-right"></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-1 text-right"><label>备注：</label></div>
                        <div class="col-lg-5"><?= Html::textarea('buyer_remark', $model->buyer_remark, ['class' => 'col-lg-12']) ?></div>
                        <div class="col-lg-6"></div>
                    </div>
                </div>
            </div>
            <div class="row nav-tabs-custom tab-pane tab0 active" id="tab_2">
                <ul class="nav nav-tabs pull-right">
                    <li class="pull-left header"><i class="fa fa-th"></i> 商品信息</li>
                </ul>
                <div class="box-body col-lg-9">
                    <div class="box-body table-responsive">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'tableOptions' => ['class' => 'table table-hover'],
                            'columns' => [
                                [
                                    'class' => 'yii\grid\SerialColumn',
                                    'visible' => false,
                                ],
                                [
                                    'label' => '商品清单',
                                    'value' => function ($model) {
                                        return common\helpers\ImageHelper::fancyBox($model->goods_image);
                                    },
                                    'filter' => false,
                                    'format' => 'raw',
                                    'headerOptions' => ['width'=>'80'],
                                ],
                                [
                                    'label' => '单价',
                                    'attribute'=>'goods_price',
                                ],
                                [
                                    'label' => '优惠金额',
                                    'attribute'=>'goods_price',
                                ],
                                [
                                    'label' => '数量',
                                    'attribute'=>'goods_num',
                                ],
                                [
                                    'label' => '小计',
                                    'attribute'=>'goods_pay_price',
                                ],
                            ]
                        ]); ?>
                    </div>
                </div>
                <div class="box-body col-lg-9">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-3 text-right"><label>快递类型：</label></div>
                                <div class="col-lg-9"></div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 text-right"><label>物流单号：</label></div>
                                <div class="col-lg-9"><?= $model->express_no ?></div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 text-right"><label>&nbsp;</label></div>
                                <div class="col-lg-9"></div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 text-right"><label>备注：</label></div>
                                <div class="col-lg-9"><?= Html::textarea('buyer_remark', $model->seller_remark, ['class' => 'col-lg-12']) ?></div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="row">
                                <div class="col-lg-5"><label>小计：</label></div>
                                <div class="col-lg-7"><?= $model->account->shipping_fee ?></div>
                            </div>
                            <div class="row">
                                <div class="col-lg-5"><label>优惠码折扣：</label></div>
                                <div class="col-lg-7"><?= $model->account->shipping_fee ?></div>
                            </div>
                            <div class="row">
                                <div class="col-lg-5"><label>运费：</label></div>
                                <div class="col-lg-7"><?= $model->account->shipping_fee ?></div>
                            </div>
                            <div class="row">
                                <div class="col-lg-5"><label>税费：</label></div>
                                <div class="col-lg-7"><?= $model->account->shipping_fee ?></div>
                            </div>
                            <div class="row">
                                <div class="col-lg-5"><label>保险：</label></div>
                                <div class="col-lg-7"><?= $model->account->shipping_fee ?></div>
                            </div>
                            <div class="row">
                                <div class="col-lg-5"><label>订单总计：</label></div>
                                <div class="col-lg-7"><?= $model->account->shipping_fee ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="col-sm-9 text-center">
                <span class="btn btn-white" onclick="history.go(-1)">返回</span>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>