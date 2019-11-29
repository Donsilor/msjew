<?php
    foreach ($skuType as $key => $val){
?>
<ul class="SKU_TYPE">
    <li is_required='0' propid='<?php echo $val['id'] ?>' sku-type-name="<?php echo $val['name'] ?>"><em>*</em><?php echo $val['name'] ?>：</li>
</ul>
<ul>
    <?php
        foreach ($val['sku_value'] as $k => $v){
    ?>
    <li><label><input type="checkbox" <?php if(in_array($v['id'], $skuValue)) echo 'checked' ?> class="sku_value" propvalid='<?php echo $v['id'] ?>' value="<?php echo $v['name'] ?>"/><?php echo $v['name'] ?></label></li>
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

<input type="hidden" id="defaultSku" attr-name="price,stock,num,aaa" attr-title="价格,库存,数量,aaaa"  ></input>
