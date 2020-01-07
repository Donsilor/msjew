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

$this->title = Yii::t('order', '详情');
$this->params['breadcrumbs'][] = ['label' => Yii::t('order', '订单'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
//
?>
    <div class="box-body nav-tabs-custom">
        <h2 class="page-header">订单详情</h2>
        <div class="tab-content">
            <div class="row nav-tabs-custom tab-pane tab0 active" id="tab_1">
                <ul class="nav nav-tabs pull-right">
                    <li class="pull-left header"><i class="fa fa-th"></i> 订单信息 <span
                                class="btn btn-success btn-sm"><?= \common\enums\OrderStatusEnum::getValue($model->order_status) ?></span>
                    </li>
                </ul>
                <div class="box-body" style="margin-left:9px">
                    <div class="row">
                        <div class="col-lg-3"><label
                                    class="col-lg-6 text-right"><?= $model->getAttributeLabel('order_sn') ?>
                                ：</label><?= $model->order_sn ?></div>
                        <div class="col-lg-3"><label
                                    class="col-lg-6 text-right"><?= $model->getAttributeLabel('language') ?>
                                ：</label><?= \common\enums\LanguageEnum::getValue($model->language) ?></div>
                        <div class="col-lg-3"><label class="col-lg-6 text-right">支付状态：</label><?= $model->order_sn ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3"><label
                                    class="col-lg-6 text-right"><?= $model->getAttributeLabel('member.realname') ?>
                                ：</label><?= $model->member->realname ?></div>
                        <div class="col-lg-3"><label class="col-lg-6 text-right">IP：</label><?= $model->order_sn ?>
                        </div>
                        <div class="col-lg-3"><label
                                    class="col-lg-6 text-right"><?= $model->getAttributeLabel('order_status') ?>
                                ：</label><?= \common\enums\OrderStatusEnum::getValue($model->order_status) ?></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3"><label
                                    class="col-lg-6 text-right"><?= $model->getAttributeLabel('member.mobile') ?>
                                ：</label><?= $model->member->mobile ?></div>
                        <div class="col-lg-3"><label
                                    class="col-lg-6 text-right"><?= $model->getAttributeLabel('payment_type') ?>
                                ：</label><?= \common\enums\PayEnum::getValue($model->payment_status) ?></div>
                        <div class="col-lg-3"><label class="col-lg-6 text-right"><?= $model->getAttributeLabel('payment_status') ?>
                                ：</label><?= \common\enums\PayStatusEnum::getValue($model->payment_status) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3"><label
                                    class="col-lg-6 text-right"><?= $model->getAttributeLabel('member.username') ?>
                                ：</label><?= $model->member->username ?></div>
                        <div class="col-lg-3"><label
                                    class="col-lg-6 text-right"><?= $model->getAttributeLabel('created_at') ?>
                                ：</label><?= Yii::$app->formatter->asDatetime($model->created_at, 'Y-M-D H:i:s') ?>
                        </div>
                        <div class="col-lg-3"><label class="col-lg-6 text-right"><?= $model->getAttributeLabel('status') ?></label>
                        <?= \common\enums\AuditStatusEnum::getValue($model->status) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3"><label
                                    class="col-lg-6 text-right"><?= $model->getAttributeLabel('address.country_name') ?>
                                ：</label><?= $model->address->country_name ?></div>
                        <div class="col-lg-3"><label
                                    class="col-lg-6 text-right"><?= $model->getAttributeLabel('order_from') ?>
                                ：</label><?= \common\enums\AppEnum::getValue($model->order_from) ?></div>
                        <div class="col-lg-3"><label
                                    class="col-lg-6 text-right"><?= $model->getAttributeLabel('follower_id') ?>
                                ：</label><?= $model->follower->realname ?></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3"><label
                                    class="col-lg-6 text-right"><?= $model->getAttributeLabel('address.city_name') ?>
                                ：</label><?= $model->address->city_name ?></div>
                        <div class="col-lg-3"></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3"><label
                                    class="col-sm-6 text-right"><?= $model->getAttributeLabel('buyer_remark') ?>
                                ：</label><?= Html::textarea('buyer_remark', $model->buyer_remark, ['class' => 'col-sm-6','readonly'=>true]) ?>
                        </div>
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
                                        $html = <<<DOM
<div class="row">
    <div class="col-lg-2">%s</div>
    <div class="col-lg-8">%s<br/>sku：%s&nbsp;%s</div>
</div>
DOM;
                                        $goods_spec = '';
                                        if($model->goods_spec){
                                            $model->goods_spec = \Yii::$app->services->goods->formatGoodsSpec($model->goods_spec);
                                            foreach ($model->goods_spec as $vo){
                                                $goods_spec .= $vo['attr_name'].":".$vo['attr_value']."&nbsp;";
                                            }
                                        }
                                        return sprintf($html,
                                            common\helpers\ImageHelper::fancyBox($model->goods_image),
                                            $model->goods_name,
                                            $model->goods_sn,
                                            $goods_spec
                                        );
                                    },
                                    'filter' => false,
                                    'format' => 'html',
                                    'headerOptions' => ['width' => '500'],
                                ],
                                'goods_num',
                                'goods_price',
//                                [
//                                    'label' => '优惠金额',
//                                    'attribute'=>'goods_price',
//                                ],                                
                                'goods_pay_price',
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
                                <div class="col-lg-3 text-right"><label><?= $model->getAttributeLabel('express_no') ?>
                                        ：</label></div>
                                <div class="col-lg-9"><?= $model->express_no ?></div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 text-right"><label>&nbsp;</label></div>
                                <div class="col-lg-9"></div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 text-right">
                                    <label><?= $model->getAttributeLabel('seller_remark') ?>：</label></div>
                                <div class="col-lg-9"><?= Html::textarea('buyer_remark', $model->seller_remark, ['class' => 'col-lg-12', 'readonly'=>'']) ?></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-5"><label><?= $model->getAttributeLabel('account.shipping_fee') ?>
                                        ：</label></div>
                                <div class="col-lg-7"><?= $model->account->shipping_fee ?></div>
                            </div>
                            <div class="row">
                                <div class="col-lg-5"><label><?= $model->getAttributeLabel('account.discount_amount') ?>
                                        ：</label></div>
                                <div class="col-lg-7"><?= $model->account->discount_amount ?></div>
                            </div>
                            <div class="row">
                                <div class="col-lg-5"><label><?= $model->getAttributeLabel('account.shipping_fee') ?>
                                        ：</label></div>
                                <div class="col-lg-7"><?= $model->account->shipping_fee ?></div>
                            </div>
                            <div class="row">
                                <div class="col-lg-5"><label><?= $model->getAttributeLabel('account.tax_fee') ?>
                                        ：</label></div>
                                <div class="col-lg-7"><?= $model->account->tax_fee ?></div>
                            </div>
                            <div class="row">
                                <div class="col-lg-5"><label><?= $model->getAttributeLabel('account.safe_fee') ?>
                                        ：</label></div>
                                <div class="col-lg-7"><?= $model->account->safe_fee ?></div>
                            </div>
                            <div class="row">
                                <div class="col-lg-5"><label><?= $model->getAttributeLabel('account.order_amount') ?>
                                        ：</label></div>
                                <div class="col-lg-7"><?= $model->account->order_amount ?></div>
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