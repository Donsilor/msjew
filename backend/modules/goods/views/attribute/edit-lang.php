<?php

use common\helpers\Html;
use yii\widgets\ActiveForm;
use common\enums\ConfirmEnum;
use common\enums\InputTypeEnum;
use common\enums\AttrTypeEnum;
use common\enums\StatusEnum;
use yii\grid\GridView;
use common\helpers\Url;
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
            <div class="box-body">

                <?php $form = ActiveForm::begin([
                    'fieldConfig' => [
                        'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}\n{hint}\n{error}</div>",
                    ],
                ]); ?>
                <?php echo Html::langTab('tab')?>      
                <div class="tab-content">  
           			<?php $newLangModel = $model->langModel();?>
              		<?php 
              		  foreach (\Yii::$app->params['languages'] as $lang_key=>$lang_name){
              		     $is_new = true;    
              		  ?>                        		
              		    <?php foreach ($model->langs as $langModel) {?>
                            <?php if($lang_key == $langModel->language){?>
                            	<!-- 编辑-->
                                <div class="tab-pane<?php echo Yii::$app->language==$lang_key?" active":"" ?>" id="tab_<?= $lang_key?>">
                                     <?= $form->field($langModel, 'attr_name')->textInput(Html::langInputOptions($langModel,$lang_key,'attr_name')) ?>
                                     <?= $form->field($langModel, 'remark')->textarea(Html::langInputOptions($langModel,$lang_key,'remark')) ?>
                              	</div>
                              	<!-- /.tab-pane -->
                            	<?php $is_new = false; break;?>
                            <?php }?>
                        <?php }?>
                        <?php if($is_new == true){?>
                        <!-- 新增 -->
                        <div class="tab-pane<?php echo Yii::$app->language==$lang_key?" active":"" ?>" id="tab_<?= $lang_key?>">
                               <?= $form->field($newLangModel, 'attr_name')->textInput(Html::langInputOptions($newLangModel,$lang_key,'attr_name')) ?>
                               <?= $form->field($newLangModel, 'remark')->textarea(Html::langInputOptions($newLangModel,$lang_key,'remark')) ?>
                        </div>
                        <!-- /.tab-pane -->
                        <?php }?>                         
                    <?php }?>
                    <?= $form->field($model, 'type_id')->widget(kartik\select2\Select2::class, [
                            'data' => Yii::$app->services->goodsType->getDropDown(),
                            'options' => ['placeholder' => '请选择'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                    ]);?>
                    <?= $form->field($model, 'attr_type')->widget(kartik\select2\Select2::class, [
        			        'data' => common\enums\AttrTypeEnum::getMap(),
                            'options' => [],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                    ]);?>      
                    <?= $form->field($model, 'input_type')->radioList(common\enums\InputTypeEnum::getMap()) ?>
            	    <?= $form->field($model, 'is_require')->radioList(common\enums\ConfirmEnum::getMap())?>       
                    <?= $form->field($model, 'status')->radioList(StatusEnum::getMap())?>
                    <?= $form->field($model, 'sort')->textInput() ?>                    
                </div>  
                <div class="form-group">
                    <div class="col-sm-12 text-center">
                        <button class="btn btn-primary" type="submit">保存</button>
                        <span class="btn btn-white" onclick="location.href='<?= Url::to(['attribute/index'])?>'">返回</span>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">属性值列表</h3>
                <div class="box-tools">
                    <?= Html::create(['attribute-value/ajax-edit-lang', 'attr_id' => $model->id], '添加属性值', [
                            'data-toggle' => 'modal',
                            'data-target' => '#ajaxModalLg',
                    ]); ?>
                </div>
            </div>                
            <div class="box-body">
    			<div class="box-body table-responsive">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-hover'],
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'visible' => false,
            ],
            'id',
            [
                    'attribute'=>'attr_value_code',
            ],
            [
                'attribute'=>'lang.attr_value_name',
            ], 
            [
                'attribute' => 'sort',
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-md-1'],
                'value' => function ($model, $key, $index, $column){
                    return  Html::sort($model->sort,['data-url'=>Url::to(['attribute-value/ajax-update'])]);
                }
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'headerOptions' => ['class' => 'col-md-1'],
                'value' => function ($model){
                    return \common\enums\StatusEnum::getValue($model->status);
                }
            ],            
            [
                'attribute'=>'updated_at',
                'value' => function ($model) {
                    return Yii::$app->formatter->asDatetime($model->updated_at);
                },
                'format' => 'raw',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{edit} {status} {delete}',
                'buttons' => [
                'edit' => function($url, $model, $key){                
                    return Html::edit(['attribute-value/ajax-edit-lang','id' => $model->id], '编辑', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModal',
                    ]);
                },
               'status' => function($url, $model, $key){
                        return Html::status($model->status,['data-url'=>Url::to(['attribute-value/ajax-update'])]);
                },
                'delete' => function($url, $model, $key){
                        return Html::delete(['attribute-value/delete', 'id' => $model->id]);
                },
                ]
            ]
    ]
    ]); ?>		


            </div> 
               
        </div>
    </div>
</div>
</div>
