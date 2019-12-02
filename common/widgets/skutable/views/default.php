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
    <li><label><input type="checkbox" <?php if(in_array($k,$val['current'])) echo 'checked' ?> class="sku_value" propvalid='<?php echo $k ?>' value="<?php echo $v ?>"/><?php echo $v ?></label></li>
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
<input type="hidden" id="defaultSku" attr-name="price,stock" attr-title="价格,库存,数量"  ></input>
</div>