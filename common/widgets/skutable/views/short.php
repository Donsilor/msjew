<ul class="SKU_TYPE">
	<li is_required='1' propid='1' sku-type-name="存储"><em>*</em>存储：</li>
</ul>
<ul>
	<li><label><input type="checkbox" class="sku_value" propvalid='11' value="16G" checked/>16G</label></li>
	<li><label><input type="checkbox" class="sku_value" propvalid='12' value="32G" />32G</label></li>
	<li><label><input type="checkbox" class="sku_value" propvalid='13' value="64G" />64G</label></li>
	<li><label><input type="checkbox" class="sku_value" propvalid='14' value="128G" />128G</label></li>
	<li><label><input type="checkbox" class="sku_value" propvalid='15' value="256G" />256G</label></li>
</ul>
<div class="clear"></div>
<ul class="SKU_TYPE">
	<li is_required='0' propid='2' sku-type-name="版本"><em>*</em>版本：</li>
</ul>
<ul>
	<li><label><input type="checkbox" class="sku_value" propvalid='21' value="中国大陆版" checked/>中国大陆版</label></li>
	<li><label><input type="checkbox" class="sku_value" propvalid='22' value="港版" />港版</label></li>
	<li><label><input type="checkbox" class="sku_value" propvalid='23' value="韩版" />韩版</label></li>
	<li><label><input type="checkbox" class="sku_value" propvalid='24' value="美版" />美版</label></li>
	<li><label><input type="checkbox" class="sku_value" propvalid='25' value="日版" />日版</label></li>
</ul>
<div class="clear"></div>
			
<ul class="SKU_TYPE">
	<li is_required='0' propid='3' sku-type-name="颜色">颜色：</li>
</ul>
<ul>
	<li><label><input type="checkbox" class="sku_value" propvalid='31' value="土豪金" checked />土豪金</label></li>
	<li><label><input type="checkbox" class="sku_value" propvalid='32' value="银白色" />银白色</label></li>
	<li><label><input type="checkbox" class="sku_value" propvalid='33' value="深空灰" />深空灰</label></li>
	<li><label><input type="checkbox" class="sku_value" propvalid='34' value="黑色" />黑色</label></li>
	<li><label><input type="checkbox" class="sku_value" propvalid='33' value="玫瑰金" />玫瑰金</label></li>
</ul>
<div class="clear"></div>
<ul class="SKU_TYPE">
	<li is_required='1' propid='4' sku-type-name="类型"><em>*</em>类型：</li>
</ul>
<ul>
	<li><label><input type="checkbox" class="sku_value" propvalid='41' value="儿童" checked/>儿童</label></li>
	<li><label><input type="checkbox" class="sku_value" propvalid='42' value="成人" />成人</label></li>
</ul>
<div class="clear"></div>
<button class="cloneSku">添加自定义sku属性</button>

<!--sku模板,用于克隆,生成自定义sku-->
<div id="skuCloneModel" style="display: none;">
	<div class="clear"></div>
	<ul class="SKU_TYPE">
		<li is_required='0' propid='' sku-type-name="">
			<a href="javascript:void(0);" class="delCusSkuType">移除</a>
			<input type="text" class="cusSkuTypeInput" />：
		</li>
	</ul>
	<ul>
		<li>
			<input type="checkbox" class="model_sku_val" propvalid='' value="" />
			<input type="text" class="cusSkuValInput" />
		</li>
		<button class="cloneSkuVal">添加自定义属性值</button>
	</ul>
	<div class="clear"></div>
</div>
<!--单个sku值克隆模板-->
<li style="display: none;" id="onlySkuValCloneModel">
	<input type="checkbox" class="model_sku_val" propvalid='' value="" />
	<input type="text" class="cusSkuValInput" />
	<a href="javascript:void(0);" class="delCusSkuVal">删除</a>
</li>
<div class="clear"></div>
<div id="skuTable"></div>