<?php

use common\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\langbox\LangBox;
use yii\base\Widget;
use common\widgets\skutable\SkuTable;

/* @var $this yii\web\View */
/* @var $model common\models\goods\Style */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('goods', 'Style');
$this->params['breadcrumbs'][] = ['label' => Yii::t('goods', 'Styles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin(); ?>
<div class="box-body nav-tabs-custom">
     <h2 class="page-header">商品发布</h2>
     <?php echo Html::tab([0=>'全部',1=>'基础信息',2=>'商品属性',3=>'图片信息',4=>'SEO优化'],0,'tab')?>
     <div class="tab-content">     
       <div class="row nav-tabs-custom tab-pane tab0 active" id="tab_1">
            <ul class="nav nav-tabs pull-right">
              <li class="pull-left header"><i class="fa fa-th"></i> 基础信息</li>
            </ul>
            <div class="box-body" style="margin-left:10px">
                <?php 
                $model->type_id = \Yii::$app->request->get("type_id")??$model->type_id;                    
                ?>            
    			<?= $form->field($model, 'type_id')->dropDownList(\Yii::$app->services->goodsType->getDropDown(),[
    			        'onchange'=>"location.href='?type_id='+this.value"        			        
    			]) ?>
                <?= $form->field($model, 'cat_id')->widget(kartik\select2\Select2::class, [
     			        'data' => Yii::$app->services->goodsCate->getDropDown(),
                        'options' => ['placeholder' => '请选择'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                ]);?>                    
    			<div class="nav-tabs-custom">
    		        <?php echo Html::langTab("tab1")?>    			      
        			<div class="tab-content" style="padding-left:10px"> 
        				<?php 
                			echo LangBox::widget(['form'=>$form,'model'=>$model,'tab'=>'tab1','fields'=>[
                			    'style_name'=>['type'=>'textInput','options'=>['maxlength' => true],'label'=>"商品名称"],
                			    'style_desc'=>['type'=>'textArea','options'=>['maxlength' => true],'label'=>"商品描述"]
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
              <li class="pull-left header"><i class="fa fa-th"></i> 商品属性</li>
            </ul>
            <div class="box-body">
                <div class="box-header with-border">
                    <h3 class="box-title">基本属性</h3>
                </div>
                <div class="box-body" style="margin-left:10px">
        
                </div>
                <!-- ./box-body -->
                <div class="box-header with-border">
                    <h3 class="box-title">销售属性</h3>
                </div>
                <div class="box-body" style="margin-left:10px">
                <?= common\widgets\skutable\SkuTable::widget(['form' => $form,
                        'model' => $model,
                        'data' =>[
                                [
                                        'id'=>1,
                                        'name'=>'颜色',
                                        'value'=>[
                                                 1=>'16G',
                                                 2=>'32G',                                                 
                                                 3=>'64G',                                              
                                                 4=>'128G',
                                        ],
                                        'current'=>[1,3,4]
                                ],
                                [
                                        'id'=>2,
                                        'name'=>'净度',
                                        'value'=>[
                                                11=>'SI',
                                                12=>'V'
                                         ],
                                        'current'=>[11]
                                ]
                                
                        ],
                        ])
                ?>
                <?= $form->field($model, 'style_sn')->textInput(['maxlength'=>true]) ?>
                <?= $form->field($model, 'goods_storage')->textInput(['maxlength'=>true]) ?>
            </div>
            <!-- ./box-body -->  
       </div>  
       <!-- ./box-body -->          
      </div>    
    
      <div class="row nav-tabs-custom tab-pane tab0 active" id="tab_3">
            <ul class="nav nav-tabs pull-right">
              <li class="pull-left header"><i class="fa fa-th"></i> 图片信息</li>
            </ul>
            <div class="box-body">
                图片信息
            </div>  
            <!-- ./box-body -->          
      </div>
     <div class="row nav-tabs-custom tab-pane tab0 active" id="tab_4">
            <ul class="nav nav-tabs pull-right">
              <li class="pull-left header"><i class="fa fa-th"></i> SEO信息</li>
            </ul>
            <div class="box-body nav-tabs-custom" style="margin-left:10px">
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
</div>
<?php ActiveForm::end(); ?>

