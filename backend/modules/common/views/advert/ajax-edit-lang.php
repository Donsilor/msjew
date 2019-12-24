<?php
use common\widgets\webuploader\Files;
use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\enums\StatusEnum;
use common\enums\SettingEnum;
use common\helpers\Html;

if(!isset($model->adv_type)){
    $model->adv_type = 1;
}
if(!isset($model->show_type)){
    $model->show_type = 2;
}
if(!isset($model->open_type)){
    $model->open_type = 1;
}
if(!isset($model->status)){
    $model->status = 1;
}



$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['ajax-edit','id' => $model['id']]),
    'fieldConfig' => [
        'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}{hint}\n{error}</div>",
    ]
]);
?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">基本信息</h4>
    </div>

    <div class="modal-body">

        <?php echo Html::langTab('tab')?>

        <div class="tab-content">

            <?php
            echo common\widgets\langbox\LangBox::widget(['form'=>$form,'model'=>$model,'tab'=>'tab',
                'fields'=>
                    [
                        'adv_name'=>['type'=>'textInput'],

                    ]]);
            ?>
        </div>


        <?= $form->field($model, 'type_id')->widget(kartik\select2\Select2::class, [
            'data' => $type,
            'options' => ['placeholder' => '请选择'],
            'pluginOptions' => [
                'allowClear' => true,
                'width'=>'200'
            ],
        ]);?>

        <?= $form->field($model, 'adv_type')->radioList(SettingEnum::$advTypeAction) ?>
        <?= $form->field($model, 'show_type')->radioList(SettingEnum::$showTypeAction) ?>
        <?= $form->field($model, 'adv_height')->textInput(['style'=>'width:100px;'])?>
        <?= $form->field($model, 'adv_width')->textInput(['style'=>'width:100px;']) ?>
        <?= $form->field($model, 'open_type')->radioList(SettingEnum::$openTypeAction); ?>
        <?= $form->field($model, 'status')->radioList(StatusEnum::getMap()); ?>
        <!-- /.tab-pane -->
    </div>
    <!-- /.tab-content -->
    </div>



    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
        <button class="btn btn-primary" type="submit">保存</button>
    </div>
<?php ActiveForm::end(); ?>