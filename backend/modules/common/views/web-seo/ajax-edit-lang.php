<?php
use common\widgets\webuploader\Files;
use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\helpers\Html;




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
            <?php if($model->isNewRecord){ ?>
                <?= $form->field($model, 'page_name')->textInput(['maxlength' => true]) ?>
            <?php }else{ ?>
                <?= $form->field($model, 'page_name')->textInput(['maxlength' => true, 'readonly' => 'true']) ?>
            <?php } ?>


            <?php
            echo common\widgets\langbox\LangBox::widget(['form'=>$form,'model'=>$model,'tab'=>'tab',
                'fields'=>
                    [
                        'meta_title'=>['type'=>'textInput'],
                        'meta_word'=>['type'=>'textArea','options'=>['rows'=>'2']],
                        'meta_desc'=>['type'=>'textArea','options'=>['rows'=>'3']],

                    ]]);
            ?>
        </div>


        <!-- /.tab-pane -->
    </div>
    <!-- /.tab-content -->
    </div>



    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
        <button class="btn btn-primary" type="submit">保存</button>
    </div>
<?php ActiveForm::end(); ?>