<?php

use common\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\setting\AdvertImages */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('advert', 'Advert Images');
$this->params['breadcrumbs'][] = ['label' => Yii::t('advert', 'Advert Images'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">基本信息</h3>
            </div>
            <div class="box-body">
                <?php $form = ActiveForm::begin([
                    'fieldConfig' => [
                        'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}\n{hint}\n{error}</div>",
                    ],
                ]); ?>
                <div class="col-sm-12">
                    <?= $form->field($model, 'adv_id')->dropDownList([]) ?>
                    <?= $form->field($model, 'adv_image')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'adv_url')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'start_time')->widget(kartik\date\DatePicker::class, [
                        'language' => 'zh-CN',
                        'layout'=>'{picker}{input}',
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd',
                            'todayHighlight' => true, // 今日高亮
                            'autoclose' => true, // 选择后自动关闭
                            'todayBtn' => true, // 今日按钮显示
                        ],
                        'options'=>[
                            'class' => 'form-control no_bor',
                        ]
                    ]) ?>
                    <?= $form->field($model, 'end_time')->widget(kartik\date\DatePicker::class, [
                        'language' => 'zh-CN',
                        'layout'=>'{picker}{input}',
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd',
                            'todayHighlight' => true, // 今日高亮
                            'autoclose' => true, // 选择后自动关闭
                            'todayBtn' => true, // 今日按钮显示
                        ],
                        'options'=>[
                            'class' => 'form-control no_bor',
                        ]
                    ]) ?>
                </div>
                <div class="form-group">
                    <div class="col-sm-12 text-center">
                        <button class="btn btn-primary" type="submit">保存</button>
                        <span class="btn btn-white" onclick="history.go(-1)">返回</span>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
