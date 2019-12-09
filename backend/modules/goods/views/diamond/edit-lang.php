<?php

use common\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\langbox\LangBox;
use yii\base\Widget;
use common\widgets\skutable\SkuTable;
use common\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\goods\Style */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('goods', 'Style');
$this->params['breadcrumbs'][] = ['label' => Yii::t('goods', 'Styles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
//
?>
<?php $form = ActiveForm::begin([
         'id' => $model->formName(),
        'enableAjaxValidation' => true,
        'validationUrl' => Url::to(['ajax-edit-lang', 'id' => $model['id']]),       
]); ?>
<div class="box-body nav-tabs-custom">
     <h2 class="page-header">裸钻发布</h2>
     <?php echo Html::tab([0=>'全部',1=>'基础信息',2=>'裸钻属性',3=>'图片信息',4=>'SEO优化'],0,'tab')?>
     <div class="tab-content">     
       <div class="row nav-tabs-custom tab-pane tab0 active" id="tab_1">
            <ul class="nav nav-tabs pull-right">
              <li class="pull-left header"><i class="fa fa-th"></i> 基础信息</li>
            </ul>
            <div class="box-body col-lg-9" style="margin-left:9px">
                <div class="row">
                    <div class="col-lg-4">
                        <?= $form->field($model, 'source_id')->widget(kartik\select2\Select2::class, [
                            'data' => Yii::$app->services->diamondSource->getDropDown(),
                            'options' => ['placeholder' => Yii::t("common",'请选择')],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);?>
                    </div>
                    <div class="col-lg-4">
                        <?= $form->field($model, 'goods_sn')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-lg-4">
                        <?= $form->field($model, 'cert_id')->textInput(['maxlength' => true]) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <?= $form->field($model, 'cert_type')->dropDownList(\common\enums\DiamondEnum::$typeOptions) ?>
                    </div>
                    <div class="col-lg-4">
                        <?= $form->field($model, 'goods_num')->textInput() ?>
                    </div>
                    <div class="col-lg-4"><?= $form->field($model, 'source_discount')->textInput(['maxlength' => true]) ?></div>
                </div>

                <?= $form->field($model, 'is_stock')->radioList(\common\enums\IsStockEnum::getMap()) ?>
                <?= $form->field($model, 'status')->radioList(\common\enums\StatusEnum::getMap()) ?>
                <div class="nav-tabs-custom ">
                    <?php echo Html::langTab("tab1")?>
                    <div class="tab-content" style="padding-left:10px">
                        <?php
                        echo LangBox::widget(['form'=>$form,'model'=>$model,'tab'=>'tab1','fields'=>[
                            'goods_name'=>['type'=>'textInput','options'=>['maxlength' => true],'label'=>Yii::t("common","商品名称")],
                            'goods_body'=>['type'=>'textArea','options'=>['maxlength' => true],'label'=>Yii::t("common","商品描述")],

                        ]]);
                        ?>
                    </div>
                </div>


    		    <!-- ./nav-tabs-custom -->
            </div>
        <!-- ./box-body -->
      </div>            
      <div class="row nav-tabs-custom tab-pane tab0 active" id="tab_2">
            <ul class="nav nav-tabs pull-right">
              <li class="pull-left header"><i class="fa fa-th"></i> 裸钻属性</li>
            </ul>
          <div class="box-body" style="margin-left:10px">
              <div class="row">
                  <div class="col-lg-3">
                      <?= $form->field($model, 'carat')->textInput() ?>
                  </div>
                  <div class="col-lg-3">
                      <?= $form->field($model, 'clarity')->dropDownList(\common\enums\DiamondEnum::$clarityOptions,['prompt'=>Yii::t("common",'请选择')]) ?>
                  </div>
                  <div class="col-lg-3">
                      <?= $form->field($model, 'cut')->dropDownList(\common\enums\DiamondEnum::$cutOptions,['prompt'=>Yii::t("common",'请选择')]) ?>
                  </div>
                  <div class="col-lg-3">
                      <?= $form->field($model, 'color')->dropDownList(\common\enums\DiamondEnum::$colorOptions,['prompt'=>Yii::t("common",'请选择')]) ?>
                  </div>
              </div>
              <div class="row">

                  <div class="col-lg-3">
                      <?= $form->field($model, 'shape')->dropDownList(\common\enums\DiamondEnum::$shapeOptions,['prompt'=>Yii::t("common",'请选择')]) ?>
                  </div>
                  <div class="col-lg-3">
                      <?= $form->field($model, 'depth_lv')->textInput(['maxlength' => true]) ?>
                  </div>
                  <div class="col-lg-3">
                      <?= $form->field($model, 'table_lv')->textInput(['maxlength' => true]) ?>
                  </div>
                  <div class="col-lg-3">
                      <?= $form->field($model, 'symmetry')->dropDownList(\common\enums\DiamondEnum::$symmetryOptions,['prompt'=>Yii::t("common",'请选择')]) ?>
                  </div>
              </div>
              <div class="row">

                  <div class="col-lg-3">
                      <?= $form->field($model, 'polish')->dropDownList(\common\enums\DiamondEnum::$polishOptions,['prompt'=>Yii::t("common",'请选择')]) ?>
                  </div>
                  <div class="col-lg-3">
                      <?= $form->field($model, 'fluorescence')->dropDownList(\common\enums\DiamondEnum::$fluorescenceOptions,['prompt'=>Yii::t("common",'请选择')]) ?>
                  </div>
                  <div class="col-lg-3">
                      <?= $form->field($model, 'market_price')->textInput(['maxlength' => true]) ?>
                  </div>
                  <div class="col-lg-3">
                      <?= $form->field($model, 'sale_price')->textInput(['maxlength' => true]) ?>
                  </div>
              </div>


          </div>
      	 <!-- ./box-body -->          
      </div>    
    
      <div class="row nav-tabs-custom tab-pane tab0 active" id="tab_3">
            <ul class="nav nav-tabs pull-right">
              <li class="pull-left header"><i class="fa fa-th"></i> 图片信息</li>
            </ul>
            <div class="box-body col-lg-9">
      <?= $form->field($model, 'goods_image')->widget(common\widgets\webuploader\Files::class, [
            'config' => [
                'pick' => [
                    'multiple' => false,
                ],

            ]
        ]); ?>
            </div>  
            <!-- ./box-body -->          
      </div>
     <div class="row nav-tabs-custom tab-pane tab0 active" id="tab_4">
            <ul class="nav nav-tabs pull-right">
              <li class="pull-left header"><i class="fa fa-th"></i> SEO信息</li>
            </ul>
            <div class="box-body nav-tabs-custom none-shadow col-lg-9" style="margin-left:10px">
                 <?php echo Html::langTab("tab4")?>           
        		  <div class="tab-content">            
                    <?php 
                    echo common\widgets\langbox\LangBox::widget(['form'=>$form,'model'=>$model,'tab'=>'tab4',
                            'fields'=>
                            [
                                'meta_title'=>['type'=>'textInput'],
                                'meta_word'=>['type'=>'textInput'],
                                'meta_desc'=>['type'=>'textArea','options'=>[]]                            
                            ]]);
            	    ?>
            	  </div>  
    		    <!-- ./tab-content -->
            </div>
            <!-- ./box-body -->
      </div>
      <!-- ./row -->
    
    </div>
    <div class="modal-footer">
        <div class="col-sm-12 text-center">
            <button class="btn btn-primary" type="submit">保存</button>
            <span class="btn btn-white" onclick="history.go(-1)">返回</span>
        </div>
	</div>
</div>

<?php ActiveForm::end(); ?>

