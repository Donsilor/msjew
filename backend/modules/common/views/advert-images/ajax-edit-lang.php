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

            <?php
            echo common\widgets\langbox\LangBox::widget(['form'=>$form,'model'=>$model,'tab'=>'tab',
                'fields'=>
                    [
                        'title'=>['type'=>'textInput'],
                        'adv_image'=>[
                            //'label'=>Yii::t("common","商品介绍"),
                            'type'=>'widget',
                            'class'=> Files::class,
                            'options'=>[
                                'config' => [
                                    // 可设置自己的上传地址, 不设置则默认地址
                                    // 'server' => '',
                                    'pick' => [
                                        'multiple' => false,
                                    ],
                                ]
                            ],//end options
                        ],//end goods_body

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
    <!-- /.tab-content -->
    </div>



    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
        <button class="btn btn-primary" type="submit">保存</button>
    </div>
<?php ActiveForm::end(); ?>