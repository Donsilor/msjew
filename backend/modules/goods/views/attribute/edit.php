<?php

use common\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model common\models\goods\Attribute */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('attribute', 'Attribute');
$this->params['breadcrumbs'][] = ['label' => Yii::t('attribute', 'Attributes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">基本信息</h3>
            </div>
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li><a href=""> 已安装的插件</a></li>
                <li><a href=""> 安装插件</a></li>
                <li class="active"> 设计新插件</a></li>
            </ul>

                    
            <div class="box-body">
                <?php $form = ActiveForm::begin([
                    'fieldConfig' => [
                        'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}\n{hint}\n{error}</div>",
                    ],
                ]); ?>
                <div class="col-sm-12">
                    <?= $form->field($model, 'attr_type')->textInput() ?>
                    <?= $form->field($model, 'cat_id')->dropDownList([]) ?>
                    <?= $form->field($model, 'input_type')->textInput() ?>
                    <?= $form->field($model, 'is_require')->radioList(\common\enums\ConfirmEnum::getMap()) ?>
                    <?= $form->field($model, 'status')->radioList(\common\enums\StatusEnum::getMap())?>
                    <?= $form->field($model, 'sort')->textInput() ?>
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
</div>
