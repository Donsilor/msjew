<?php

use common\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\langbox\LangBox;
use yii\base\Widget;
use common\widgets\skutable\SkuTable;
use common\helpers\Url;
use common\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\goods\Style */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('goods', 'Style');
$this->params['breadcrumbs'][] = ['label' => Yii::t('goods', 'Styles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$model->style_attr = $model->style_attr?json_decode($model->style_attr,true):[];
$model->style_spec = $model->style_spec?json_decode($model->style_spec,true):[];
//
?>
<?php $form = ActiveForm::begin([
        'id' => $model->formName(),
        'enableAjaxValidation' => true,
        'validationUrl' => Url::to(['ajax-edit-lang', 'id' => $model['id']]),       
]); ?>
<div class="box-body nav-tabs-custom">
     <h2 class="page-header">商品发布</h2>
     <?php $tab_list = [0=>'全部',1=>'基础信息',2=>'商品属性',3=>'图文信息',4=>'SEO优化'];?>
     <?php echo Html::tab($tab_list,0,'tab')?>
     <div class="tab-content">     
       <div class="row nav-tabs-custom tab-pane tab0 active" id="tab_1">
            <ul class="nav nav-tabs pull-right">
              <li class="pull-left header"><i class="fa fa-th"></i> <?= $tab_list[1]??'';?></li>
            </ul>
            <div class="box-body" style="margin-left:9px">
                <?php 
                $type_id = Yii::$app->request->get("type_id");
                $_type_id = Yii::$app->request->get("_type_id",$type_id);
                $model->type_id = $model->type_id?? $_type_id;
                ?> 
                 <div class="row">
                 <div class="col-lg-4">         
        			<?= $form->field($model, 'type_id')->dropDownList(\Yii::$app->services->goodsType->getGrpDropDown($type_id),[
        			        'prompt' => '请选择',
        			        'onchange'=>"location.href='?_type_id='+this.value+'&type_id={$type_id}'",
        			        'disabled'=>$model->isNewRecord?null:'disabled',
        			]) ?> 
    			</div>
    			<div class="col-lg-4"><?= $form->field($model, 'style_sn')->textInput(['maxlength'=>true]) ?></div>
                </div>                
                <div class="row">
                    <div class="col-lg-4"><?= $form->field($model, 'sale_volume')->textInput(['maxlength'=>true,'disabled'=>true]) ?></div>
                    <div class="col-lg-4"><?= $form->field($model, 'virtual_volume')->textInput(['maxlength'=>true]) ?></div>
                </div>
                <div class="row">
                    <div class="col-lg-4"><?= $form->field($model, 'goods_clicks')->textInput(['maxlength'=>true,'disabled'=>true]) ?></div>
                    <div class="col-lg-4"><?= $form->field($model, 'virtual_clicks')->textInput(['maxlength'=>true]) ?></div>
                </div>                                  
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
                <div class="row">
                    <div class="col-lg-3"><?= $form->field($model, 'status')->radioList(\common\enums\FrameEnum::getMap()) ?></div>
                    <?php $model->recommend_type = !empty($model->recommend_type)?explode(',', $model->recommend_type):null;?>
                    <div class="col-lg-3"><?= $form->field($model, 'recommend_type')->checkboxList(\common\enums\RecommendEnum::getMap()) ?></div>
                </div>

    		    <!-- ./nav-tabs-custom -->
            </div>
        <!-- ./box-body -->
      </div>            
      <div class="row nav-tabs-custom tab-pane tab0 active" id="tab_2">
            <ul class="nav nav-tabs pull-right">
              <li class="pull-left header"><i class="fa fa-th"></i> <?= $tab_list[2]??'';?></li>
            </ul>
            <div class="box-body col-lg-12">
               <?php               
                $attr_list_all = \Yii::$app->services->goodsAttribute->getAttrListByTypeId($model->type_id);
                $type_sale = common\enums\AttrTypeEnum::TYPE_SALE;
                if(!isset($attr_list_all[$type_sale])){
                    $attr_list_all[$type_sale] = [];
                }
                foreach ($attr_list_all as $attr_type=>$attr_list){
                    ?>
                    <div class="box-header with-border">
                    	<h3 class="box-title"><?= common\enums\AttrTypeEnum::getValue($attr_type)?></h3>
                	</div>
                    <div class="box-body" style="margin-left:10px">
                      <?php
                      //如果是销售属性
                      if($attr_type == common\enums\AttrTypeEnum::TYPE_SALE){
                          ?>
                            <div class="row">
                                <div class="col-lg-4"><?= $form->field($model, 'sale_price')->textInput(['maxlength'=>true]) ?></div>
                                <div class="col-lg-4"><?=  $form->field($model, 'cost_price')->textInput(['maxlength'=>true]) ?></div>
                                <div class="col-lg-4"><?= $form->field($model, 'market_price')->textInput(['maxlength'=>true]) ?></div>
                            </div> 
   							<div class="row">
   							    <div class="col-lg-4"><?=  $form->field($model, 'goods_storage')->textInput(['maxlength'=>true]) ?></div>
   							    
                            </div> 
                          <?php 
                          $data = [];                          
                          foreach ($attr_list as $k=>$attr){   
                              $values = Yii::$app->services->goodsAttribute->getValuesByAttrId($attr['id']);
                              $data[] = [
                                  'id'=>$attr['id'],
                                  'name'=>$attr['attr_name'],
                                  'value'=>Yii::$app->services->goodsAttribute->getValuesByAttrId($attr['id']),
                                  'current'=>$model->style_spec['a'][$attr['id']]??[]
                              ];   
                          }
                         
                          if(!empty($data)){
                             echo common\widgets\skutable\SkuTable::widget(['form' => $form,'model' => $model,'data' =>$data,'name'=>'Style[style_spec]']);
                             ?>
                             <script type="text/javascript">
                                 $(function(){  
                                  	$('form#Style').on('submit', function (e) {
                                		var r = checkSkuInputData();
                                    	if(!r){
                                        	e.preventDefault();
                                    	}
                                    });
                                 });
                             </script>
                             <?php 
                          }
                      }else{                              
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
                                      case common\enums\InputTypeEnum::INPUT_MUlTI :{
                                          $input = $form->field($model,$attr_field_name)->checkboxList($attr_values)->label($attr['attr_name']);
                                          break;
                                      }
                                      default:{
                                          $input = $form->field($model,$attr_field_name)->dropDownList($attr_values,['prompt'=>'请选择'])->label($attr['attr_name']);
                                          break;
                                      }
                                  }//end switch
                      ?>
                           <?php 
                           $collLg = 4;
                           /* if($attr_type == common\enums\AttrTypeEnum::TYPE_SERVER){
                                $collLg = 12;
                           } */?>
                              <?php if ($k % 3 ==0){ ?><div class="row"><?php }?>
    							<div class="col-lg-<?=$collLg?>"><?= $input ?></div>
                              <?php if(($k+1) % 3 == 0 || ($k+1) == count($attr_list)){?></div><?php }?>
                      <?php 
                              }//end foreach $attr_list
                              $show_storage = empty($attr_list)?true:false; 
                       }?>
                    </div>
                    <!-- ./box-body -->
                    <?php 
                }//end foreach $attr_list_all
                ?>  
           </div>  
      	 <!-- ./box-body -->          
      </div>    
    
      <div class="row nav-tabs-custom tab-pane tab0 active" id="tab_3">
            <ul class="nav nav-tabs pull-right">
              <li class="pull-left header"><i class="fa fa-th"></i> <?= $tab_list[3]??'';?></li>
            </ul>
            <div class="box-body col-lg-9">
            	<div class="row">
                  <?php $model->goods_images = !empty($model->goods_images)?explode(',', $model->goods_images):null;?>      
                  <?= $form->field($model, 'goods_images')->widget(common\widgets\webuploader\Files::class, [
                        'config' => [
                            'pick' => [
                                'multiple' => true,
                            ],
                            /* 'formData' => [
                                    'drive' => 'oss',// 默认本地 支持 qiniu/oss 上传
                                    'thumb' => [
                                            [
                                                    'width' => 800,
                                                    'height' => 800,
                                            ]
                                    ]
                            ], */
                        ]
                  ]); ?>
                </div>
                <div class="row">
                    <div class="col-lg-5">
                        <?= $form->field($model, 'style_3ds')->textInput(['maxlength' => true, 'id'=>'ds3']) ?>
                    </div>
                    <div class="col-lg-5">
                        <?= Html::button('预览',['class'=>'btn btn-info btn-sm','style'=>'margin-top:25px;','onclick'=>"view_3ds()"]) ?>
                    </div>

                </div>

                <div class="row nav-tabs-custom">
    		        <?php echo Html::langTab("tab_body")?>    			      
        			<div class="tab-content " style="padding-left:10px"> 
        				<?php 
                			echo LangBox::widget(['form'=>$form,'model'=>$model,'tab'=>'tab_body','fields'=>[
                			        'goods_body'=>[
                			            //'label'=>Yii::t("common","商品介绍"),
            			                'type'=>'widget',
            			                'class'=> \common\widgets\ueditor\UEditor::class,
            			                'options'=>[
            			                         /* 'formData' => [
            			                                //'drive' => 'qiniu', // 默认本地 支持qiniu/oss/cos 上传
            			                                'poster' => false, // 上传视频时返回视频封面图，开启此选项需要安装 ffmpeg 命令
            			                                'thumb' => [
            			                                        [
        			                                                'width' => 800,
        			                                                'height' => 800,
            			                                        ]
            			                                ]
            			                        ],  */
            			                ],//end options            			                                			                
                			        ],//end goods_body
                			]]);
            			?>
        			</div>                  
                </div> <!-- ./nav-tabs-custom -->    
            </div>  
            <!-- ./box-body -->          
      </div>
     <div class="row nav-tabs-custom tab-pane tab0 active" id="tab_4">
            <ul class="nav nav-tabs pull-right">
              <li class="pull-left header"><i class="fa fa-th"></i> <?= $tab_list[4]??'';?></li>
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
<script type="text/javascript">
$(function(){ 
	$(document).on("click",'.control-label',function(){
         var checked = false; 
		 if(!$(this).hasClass('checked')){
			 checked = true;
			 $(this).addClass('checked');
		 }else{
			 $(this).removeClass('checked');
		 }

         $(this).parent().find("input[type*='checkbox']").prop("checked",checked);
	});
	//批量商品编码复制
	$(document).on("click",'.batch-goods_sn',function(){
		var hasEdit = false;
		var fromValue = $("#style-style_sn").val();
		if(fromValue ==""){
             alert("<?= Yii::t("goods","请先填写款式编号")?>");
             return false;
		}
		$("#skuTable tr[class*='sku_table_tr']").each(function(){
			var skuValue = $(this).find(".setsku-goods_sn").val();
        	if(skuValue != '' && skuValue != fromValue){
        		hasEdit = true;
        		return ;
        	}
        });
        if(hasEdit === true){
           	 if(!confirm("<?= Yii::t("goods","商品编码已修改过,是否覆盖")?>?")){
               	return false;
           	 }
        }
    	$("#skuTable tr[class*='sku_table_tr']").each(function(){
        	if($(this).find(".setsku-status").val() == 1){
        		$(this).find(".setsku-goods_sn").val(fromValue);
        	}
        });

	});

	$(document).on("click",'.batch-market_price',function(){
		var hasEdit = false;
		var fromValue = $("#style-market_price").val();
		if(fromValue ==""){
             alert("<?= Yii::t("goods","请先填写市场价")?>");
             return false;
		}
		$("#skuTable tr[class*='sku_table_tr']").each(function(){
			var skuValue = $(this).find(".setsku-market_price").val();
        	if(skuValue != '' && skuValue != fromValue){
        		hasEdit = true;
        		return ;
        	}
        });
        if(hasEdit === true){
           	 if(!confirm("<?= Yii::t("goods","市场价已修改过,是否覆盖")?>?")){
               	return false;
           	 }
        }
    	$("#skuTable tr[class*='sku_table_tr']").each(function(){
        	if($(this).find(".setsku-status").val() == 1){
        		$(this).find(".setsku-market_price").val(fromValue);
        	}
        });
	});
	//销售价批量填充
	$(document).on("click",'.batch-sale_price',function(){
		var hasEdit = false;
		var fromValue = $("#style-sale_price").val();
		if(fromValue ==""){
             alert("<?= Yii::t("goods","请先填写销售价")?>");
             return false;
		}
		$("#skuTable tr[class*='sku_table_tr']").each(function(){
			var skuValue = $(this).find(".setsku-sale_price").val();
        	if(skuValue != '' && skuValue != fromValue){
        		hasEdit = true;
        		return ;
        	}
        });
        if(hasEdit === true){
           	 if(!confirm("<?= Yii::t("goods","销售价已修改过,是否覆盖")?>?")){
               	return false;
           	 }
        }
    	$("#skuTable tr[class*='sku_table_tr']").each(function(){
        	if($(this).find(".setsku-status").val() == 1){
        		$(this).find(".setsku-sale_price").val(fromValue);
        	}
        });
	});
	//成本价批量填充
	$(document).on("click",'.batch-cost_price',function(){
		var hasEdit = false;
		var fromValue = $("#style-cost_price").val();
		if(fromValue ==""){
             alert("<?= Yii::t("goods","请先填写成本价")?>");
             return false;
		}
		$("#skuTable tr[class*='sku_table_tr']").each(function(){
			var skuValue = $(this).find(".setsku-cost_price").val();
        	if(skuValue != '' && skuValue != fromValue){
        		hasEdit = true;
        		return ;
        	}
        });
        if(hasEdit === true){
           	 if(!confirm("<?= Yii::t("goods","销售价已修改过,是否覆盖")?>?")){
               	return false;
           	 }
        }
    	$("#skuTable tr[class*='sku_table_tr']").each(function(){
        	if($(this).find(".setsku-status").val() == 1){
        		$(this).find(".setsku-cost_price").val(fromValue);
        	}
        });
	});
	//库存批量填充
	$(document).on("click",'.batch-goods_storage',function(){
		var hasEdit = false;
		var fromValue = $("#style-goods_storage").val();
		var r = /^\+?[1-9][0-9]*$/;
		if((fromValue = prompt("<?= Yii::t("goods","请输入库存数量")?>","10")) && !r.test(fromValue)){
             alert("<?= Yii::t("goods","库存数量不合法")?>");
             return false;
		}
		$("#skuTable tr[class*='sku_table_tr']").each(function(){
			var skuValue = $(this).find(".setsku-goods_storage").val();
        	if(skuValue != '' && skuValue != fromValue){
        		hasEdit = true;
        		return ;
        	}
        });
        if(hasEdit === true){
           	 if(!confirm("<?= Yii::t("goods","商品库存已修改过,是否覆盖")?>?")){
               	return false;
           	 }
        }
    	$("#skuTable tr[class*='sku_table_tr']").each(function(){
        	if($(this).find(".setsku-status").val() == 1){
        		$(this).find(".setsku-goods_storage").val(fromValue);
        	}
        });
        goodsStroageSum();
	});
	$(document).on("blur",'.setsku-goods_storage',function(){
    	goodsStroageSum();
	});
	$(document).on("click",'.sku-status',function(){
    	goodsStroageSum();
	});	
	function goodsStroageSum(){
		var total = 0;
		$("#skuTable tr[class*='sku_table_tr']").each(function(){
        	if($(this).find(".setsku-status").val() == 1){
        		var storage = $(this).find(".setsku-goods_storage").val();
        		if(parseInt(storage)){
        			total += parseInt(storage);
        		}
        	}
        }); 
		$("#style-goods_storage").val(total).attr('readonly',true);
        return total; 
	}
	/*
	//基础信息销售价计算
	function salePriceCalc(){
		var priceList = [];
		var minPrice = 0;
		var hasOne = false;	
		$("#skuTable tr[class*='sku_table_tr']").each(function(){			
        	if($(this).find(".setsku-status").val() == 1 && (salePrice = $(this).find(".setsku-sale_price").val())){
        		priceList.push(salePrice);
        	}
        }); 
        if(!priceList){
        	$("#style-sale_price").val().attr('readonly',false);
            return minPrice;
        }
        priceList.sort(function(v1,v2){return v1-v2;});  
        minPrice = priceList[0];  
		$("#style-sale_price").val(minPrice).attr('readonly',true);
        return minPrice; 
	}
	//基础信息销售价计算
	function marketPriceCalc(){
		var priceList = [];
		var maxPrice = 0;
		$("#skuTable tr[class*='sku_table_tr']").each(function(){			
        	if($(this).find(".setsku-status").val() == 1 && (price = $(this).find(".setsku-market_price").val())){
        		priceList.push(price);
        	}
        }); 
        if(!priceList){
        	$("#style-market_price").val().attr('readonly',false);
            return minPrice;
        }
        priceList.sort(function(v1,v2){return v2-v1;});  
        minPrice = priceList[0];  
		//$("#style-market_price").val(minPrice).attr('readonly',true);
        return minPrice; 
	} */

});
</script>