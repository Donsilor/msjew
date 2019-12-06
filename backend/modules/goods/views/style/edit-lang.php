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
     <h2 class="page-header">商品发布</h2>
     <?php echo Html::tab([0=>'全部',1=>'基础信息',2=>'商品属性',3=>'图片信息',4=>'SEO优化'],0,'tab')?>
     <div class="tab-content">     
       <div class="row nav-tabs-custom tab-pane tab0 active" id="tab_1">
            <ul class="nav nav-tabs pull-right">
              <li class="pull-left header"><i class="fa fa-th"></i> 基础信息</li>
            </ul>
            <div class="box-body col-lg-9" style="margin-left:9px">
                <?php 
                $model->type_id = \Yii::$app->request->get("type_id")??$model->type_id;                    
                ?>          
    			<?= $form->field($model, 'type_id')->dropDownList(\Yii::$app->services->goodsType->getDropDown(),[
    			        'onchange'=>"location.href='?type_id='+this.value",
    			        'disabled'=>$model->isNewRecord?null:'disabled',
    			]) ?> 
                <?= $form->field($model, 'cat_id')->widget(kartik\select2\Select2::class, [
     			        'data' => Yii::$app->services->goodsCate->getDropDown(),
                        'options' => ['placeholder' => Yii::t("common",'请选择')],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                ]);?>
                <?= $form->field($model, 'style_sex')->radioList(common\enums\StyleSexEnum::getMap()) ?> 
                <?= $form->field($model, 'style_sn')->textInput(['maxlength'=>true]) ?>
                <?= $form->field($model, 'market_price')->textInput(['maxlength'=>true]) ?>
                <?= $form->field($model, 'sale_price')->textInput(['maxlength'=>true]) ?>                                 
    			<div class="nav-tabs-custom ">
    		        <?php echo Html::langTab("tab1")?>    			      
        			<div class="tab-content" style="padding-left:10px"> 
        				<?php 
                			echo LangBox::widget(['form'=>$form,'model'=>$model,'tab'=>'tab1','fields'=>[
                			        'style_name'=>['type'=>'textInput','options'=>['maxlength' => true],'label'=>Yii::t("common","商品名称")],
                			        'style_desc'=>['type'=>'textArea','options'=>['maxlength' => true],'label'=>Yii::t("common","商品描述")]
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
            <div class="box-body col-lg-10">
               <?php 
                $attr_list_all = \Yii::$app->services->goodsAttribute->getAttrListByTypeId($model->type_id);
                foreach ($attr_list_all as $attr_type=>$attr_list){
                    ?>
                    <div class="box-header with-border">
                    	<h3 class="box-title"><?= common\enums\AttrTypeEnum::getValue($attr_type)?></h3>
                	</div>
                    <div class="box-body" style="margin-left:10px">
                      <?php 
                      //如果是销售属性
                      if($attr_type == common\enums\AttrTypeEnum::TYPE_SALE){
                          $data = [];
                          $model->style_spec = json_decode($model->style_spec,true);
                          foreach ($attr_list as $k=>$attr){   
                              $values = Yii::$app->services->goodsAttribute->getValuesByAttrId($attr['id']);
                              $data[] = [
                                  'id'=>$attr['id'],
                                  'name'=>$attr['attr_name'],
                                  'value'=>Yii::$app->services->goodsAttribute->getValuesByAttrId($attr['id']),
                                  'current'=>$model->style_spec[0][$attr['id']]??[]
                              ];   
                          }
                         
                          if(!empty($data)){
                             echo common\widgets\skutable\SkuTable::widget(['form' => $form,'model' => $model,'data' =>$data,'name'=>'Style[style_spec]']);
                          }
                       } else if($attr_type == common\enums\AttrTypeEnum::TYPE_BASE) {
                              $model->style_attr = json_decode($model->style_attr,true);
                              foreach ($attr_list as $k=>$attr){ 
                                  $attr_field = $attr['is_require']==1?'attr_require':'attr_custom';                                  
                                  $attr_field_name = "{$attr_field}[{$attr['id']}]";                                  
                                  $model->{$attr_field} = $model->style_attr;//$style_attr[$attr['id']]??'';
                                  //通用属性值列表
                                  $attr_values = Yii::$app->services->goodsAttribute->getValuesByAttrId($attr['id']);                                  
                                  switch ($attr['input_type']){
                                      case common\enums\InputTypeEnum::INPUT_TEXT :{
                                          $input = $form->field($model,$attr_field_name)->textInput()->label($attr['attr_name']);
                                          break;
                                      }
                                      case common\enums\InputTypeEnum::INPUT_RADIO :{
                                          $input = $form->field($model,$attr_field_name)->radioList($attr_values)->label($attr['attr_name']);
                                          break;
                                      }
                                      default:{
                                          $input = $form->field($model,$attr_field_name)->dropDownList($attr_values,['prompt'=>'请选择'])->label($attr['attr_name']);
                                          break;
                                      }
                                  }//end switch
                      ?>
                              <?php if ($k % 3 ==0){ ?><div class="row"><?php }?>
    							<div class="col-lg-4"><?= $input ?></div>
                              <?php if(($k+1) % 3 == 0 || ($k+1) == count($attr_list)){?></div><?php }?>
                      <?php 
                              }//end foreach $attr_list
                          
                       }?>
                    </div>
                    <!-- ./box-body -->
                    <?php 
                }//end foreach $attr_list_all
                ?>
                <div class="box-body" style="margin-left:10px">
                <?= $form->field($model, 'goods_storage')->textInput(['maxlength'=>true]) ?>
                </div>
                  
           </div>  
      	 <!-- ./box-body -->          
      </div>    
    
      <div class="row nav-tabs-custom tab-pane tab0 active" id="tab_3">
            <ul class="nav nav-tabs pull-right">
              <li class="pull-left header"><i class="fa fa-th"></i> 图片信息</li>
            </ul>
            <div class="box-body col-lg-9">
      <?php $model->goods_images = !empty($model->goods_images)?explode(',', $model->goods_images):null;?>      
      <?= $form->field($model, 'goods_images')->widget(common\widgets\webuploader\Files::class, [
            'config' => [
                'pick' => [
                    'multiple' => true,
                ],
                'formData' => [
                    'drive' => 'local',// 默认本地 支持 qiniu/oss 上传
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


