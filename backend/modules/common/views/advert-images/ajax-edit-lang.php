<?php
use common\widgets\webuploader\Files;
use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\helpers\Html;
use kartik\select2\Select2;




$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['ajax-edit','id' => $model['id']]),
    'fieldConfig' => [
        'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}\n{hint}\n{error}</div>",
    ]
]);
?>
    <div class="row">
    <div class="col-lg-12">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">基本信息</h3>
        </div>
        <div class="modal-body">

        <?php echo Html::langTab('tab')?>

        <div class="tab-content">
            <?= $form->field($model, 'adv_id')->widget(Select2::class, [
                'data' => $advert,
                'options' => ['placeholder' => '请选择'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);?>

        </div>

        <?= $form->field($model, 'adv_image')->widget(common\widgets\webuploader\Files::class, [
            'config' => [
                'pick' => [
                    'multiple' => false,
                ],
                'formData' => [],
            ]
        ]); ?>

        <?php $model->area_ids = !empty($model->area_ids)?explode(',', $model->area_ids):null;?>
        <?= $form->field($model, 'area_ids')->checkboxList(common\enums\AreaEnum::getMap()) ?>
        <?= $form->field($model, 'type_id')->widget(kartik\select2\Select2::class, [
            'data' => $type,
            'options' => ['placeholder' => '请选择'],
            'pluginOptions' => [
                'allowClear' => true,
                'width'=>'200'
            ],
        ]);?>




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

        <?= $form->field($model, 'sort')->textInput() ?>
        <!-- /.tab-pane -->
    </div>
    </div>
    </div>
    </div>
    <!-- /.tab-content -->
    </div>



    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
        <button class="btn btn-primary" type="submit">保存</button>
    </div>
<?php ActiveForm::end(); ?>