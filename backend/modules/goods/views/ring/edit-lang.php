<?php

use common\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\goods\Ring */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('goods_ring', 'Ring');
$this->params['breadcrumbs'][] = ['label' => Yii::t('goods_ring', 'Rings'), 'url' => ['index']];
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
                    <?php echo Html::langTab('tab')?>
                    <div class="tab-content">

                        <?php
                        echo common\widgets\langbox\LangBox::widget(['form'=>$form,'model'=>$model,'tab'=>'tab',
                            'fields'=>
                                [
                                    'ring_name'=>['type'=>'textInput'],

                                ]]);
                        ?>
                    </div>




                    <div class="form-group field-ring-ring_sn">
                        <div class="col-sm-2 text-right">
                            <label class="control-label" for="ring-goods">商品</label>
                        </div>
                        <div class="col-sm-10">

                            <?= Html::create(['select-style'], '添加商品', [
                                'class' => 'btn btn-primary btn-xs openIframe',
                            ])?>
                            <div class="help-block"></div>
                        </div>
                    </div>
                    <?= $form->field($model, 'ring_sn')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'ring_image')->widget(common\widgets\webuploader\Files::class, [
                        'type' => 'images',
                        'theme' => 'default',
                        'themeConfig' => [],
                        'config' => [
                            'pick' => [
                                'multiple' => true,
                            ],

                        ]
                    ]); ?>


                    <?= $form->field($model, 'ring_style')->widget(kartik\select2\Select2::class, [
                        'data' => common\enums\StyleEnum::getMap(),
                        'options' => ['placeholder' => '请选择'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);?>
                    <?= $form->field($model, 'sale_price')->textInput(['maxlength' => true]) ?>


                    <?= $form->field($model, 'status')->radioList(\common\enums\FrameEnum::getMap()) ?>

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
