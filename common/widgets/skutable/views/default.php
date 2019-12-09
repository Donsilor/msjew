<script>
//已保存属性配置
var alreadySetSkuVals = <?= isset($model->style_spec['b'])?json_encode($model->style_spec['b']):"{}"?>;
var inputName = '<?= $name?>';//input控件名称
var lang = '<?= Yii::$app->language?>';//当前语言
</script>
<div id="skuTableBox">
<?php
foreach ($data as $key => $val){
?>
<ul class="sku_type">
    <li is_required='0' propid='<?php echo $val['id'] ?>' sku-type-name="<?php echo $val['name'] ?>"><em>*</em><?php echo $val['name'] ?>：</li>
</ul>
<ul class="sku_value">
    <?php
        foreach ($val['value'] as $k => $v){
    ?>
    <li><label><input type="checkbox" name="<?= $name?>[a][<?=$val['id']?>][]" <?php if(in_array($k,$val['current'])) echo 'checked' ?> class="sku_value" propvalid='<?php echo $k ?>' title="<?php echo $v ?>" value="<?php echo $k ?>"/><?php echo $v ?></label></li>
    <?php } ?>
</ul>
<div class="clear"></div>
<?php } ?>

<!--单个sku值克隆模板-->
<li style="display: none;" id="onlySkuValCloneModel">
	<input type="checkbox" class="model_sku_val" propvalid='' value="" />
	<input type="text" class="cusSkuValInput" />
	<a href="javascript:void(0);" class="delCusSkuVal">删除</a>
</li>
<div class="clear"></div>
<div id="skuTable"></div>
<?php foreach ($inputAttrs as $input){?>
	<input type="hidden" name="skuInput[]" attr-require="<?= $input['require']?>" attr-name="<?= $input['name']?>" attr-title="<?= $input['title']?>" attr-batch="<?= $input['batch']?>" value="<?= $input['name']?>"/>
<?php }?>
<!--<input type="button" class="getSetSkuVal" value="校验数据" />  -->
</div>
