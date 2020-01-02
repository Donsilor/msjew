<?php

use common\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\langbox\LangBox;
use yii\base\Widget;
use common\widgets\skutable\SkuTable;
use common\helpers\Url;
use common\helpers\ArrayHelper;

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
    <h2 class="page-header">订单预览</h2>
    <?php $tab_list = [0 => '全部', 1 => '基础信息', 2 => '商品明细', 3 => '图文信息', 4 => 'SEO优化']; ?>
    <?php echo Html::tab($tab_list, 0, 'tab') ?>
    <div class="tab-content">
        <div class="row nav-tabs-custom tab-pane tab0 active" id="tab_1">
            <ul class="nav nav-tabs pull-right">
                <li class="pull-left header"><i class="fa fa-th"></i> <?= $tab_list[1] ?? ''; ?></li>
            </ul>
            <div class="box-body" style="margin-left:9px">
                <div class="row">
                    <div class="col-lg-2">test：</div>
                    <div class="col-lg-4">test</div>
                    <div class="col-lg-2">test：</div>
                    <div class="col-lg-4">test</div>
                </div>
                <div class="row">
                    <div class="col-lg-4"><?= $form->field($model->account, 'discount_amount')->textInput() ?></div>
                    <div class="col-lg-4"></div>
                </div>
                <div class="row">
                    <div class="col-lg-4"></div>
                    <div class="col-lg-4"></div>
                </div>
                <div class="row">
                    <div class="col-lg-3"></div>
                </div>

                <!-- ./nav-tabs-custom -->
            </div>
            <!-- ./box-body -->
        </div>
        <div class="row nav-tabs-custom tab-pane tab0 active" id="tab_2">
            <ul class="nav nav-tabs pull-right">
                <li class="pull-left header"><i class="fa fa-th"></i> <?= $tab_list[2] ?? ''; ?></li>
            </ul>
            <div class="box-body col-lg-12">
                    <div class="box-header with-border">
                        <h3 class="box-title"></h3>
                    </div>
                    <div class="box-body" style="margin-left:10px">
                            <div class="row">
                                <div class="col-lg-4"></div>
                                <div class="col-lg-4"></div>
                                <div class="col-lg-4"></div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4"></div>
                            </div>
                    </div>
            </div>
            <!-- ./box-body -->
        </div>

        <div class="row nav-tabs-custom tab-pane tab0 active" id="tab_3">
            <ul class="nav nav-tabs pull-right">
                <li class="pull-left header"><i class="fa fa-th"></i> <?= $tab_list[3] ?? ''; ?></li>
            </ul>
            <div class="box-body col-lg-9">
                <div class="row">

                </div>
                <div class="row">
                    <div class="col-lg-5">
                    </div>
                    <div class="col-lg-5">
                    </div>
                </div>

                <div class="row nav-tabs-custom">
                    <?php echo Html::langTab("tab_body") ?>
                    <div class="tab-content " style="padding-left:10px">
                    </div>
                </div> <!-- ./nav-tabs-custom -->
            </div>
            <!-- ./box-body -->
        </div>
        <div class="row nav-tabs-custom tab-pane tab0 active" id="tab_4">
            <ul class="nav nav-tabs pull-right">
                <li class="pull-left header"><i class="fa fa-th"></i> <?= $tab_list[4] ?? ''; ?></li>
            </ul>
            <div class="box-body nav-tabs-custom none-shadow col-lg-9" style="margin-left:10px">
                <?php echo Html::langTab("tab4") ?>
                <div class="tab-content">

                </div>
                <!-- ./tab-content -->
            </div>
            <!-- ./box-body -->
        </div>
        <!-- ./row -->

    </div>
    <div class="modal-footer">
        <div class="col-sm-12 text-center">
            <span class="btn btn-white" onclick="history.go(-1)">返回</span>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>