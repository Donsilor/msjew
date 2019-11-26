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

        <ul class="nav nav-tabs">
            <?php foreach (\Yii::$app->params['languages'] as $lang_key=>$lang_name){?>
                <li class="<?php echo Yii::$app->language==$lang_key?"active":"" ?>">
                    <a href="#tab_<?php echo $lang_key?>" data-toggle="tab" aria-expanded="false"><?php echo $lang_name?></a>
                </li>
            <?php }?>
        </ul>

        <div class="tab-content">
            <?= $form->field($model, 'page_name')->textInput(['maxlength' => true]) ?>
            <?php $newLangModel = $model->langModel();?>
            <?php
            foreach (\Yii::$app->params['languages'] as $lang_key=>$lang_name){
                $is_new = true;
                ?>
                <?php foreach ($model->langs as $langModel) {?>
                    <?php if($lang_key == $langModel->language){?>
                        <!-- 编辑-->
                        <div class="tab-pane<?php echo Yii::$app->language==$lang_key?" active":"" ?>" id="tab_<?= $lang_key?>">
                            <?= $form->field($langModel, 'meta_title')->textInput(['name'=>Html::langInputName($langModel,$lang_key,"meta_title")]) ?>
                            <?= $form->field($langModel, 'meta_word')->textArea(['name'=>Html::langInputName($langModel,$lang_key,"meta_word"),'rows'=>'2']) ?>
                            <?= $form->field($langModel, 'meta_desc')->textArea(['name'=>Html::langInputName($langModel,$lang_key,"meta_desc"),'rows'=>'3']) ?>
                        </div>
                        <!-- /.tab-pane -->
                        <?php $is_new = false; break;?>
                    <?php }?>
                <?php }?>
                <?php if($is_new == true){?>
                    <!-- 新增 -->
                    <div class="tab-pane<?php echo Yii::$app->language==$lang_key?" active":"" ?>" id="tab_<?= $lang_key?>">
                        <?= $form->field($newLangModel, 'meta_title')->textInput(['name'=>Html::langInputName($newLangModel,$lang_key,"meta_title")]) ?>
                        <?= $form->field($newLangModel, 'meta_word')->textArea(['name'=>Html::langInputName($newLangModel,$lang_key,"meta_word"),'rows'=>'2']) ?>
                        <?= $form->field($newLangModel, 'meta_desc')->textArea(['name'=>Html::langInputName($newLangModel,$lang_key,"meta_desc"),'rows'=>'3']) ?>
                    </div>
                    <!-- /.tab-pane -->
                <?php }?>
            <?php }?>
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