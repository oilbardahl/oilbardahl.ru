<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
echo ShowError($arResult["ERROR_MESSAGE"]);

$bDelayColumn  = false;
$bDeleteColumn = false;
$bWeightColumn = false;
$bPropsColumn  = false;
$bPriceType    = false;

if ($normalCount > 0):
	foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader):
		$arHeader["name"] = (isset($arHeader["name"]) ? (string)$arHeader["name"] : '');
		if ($arHeader["name"] == '')
			$arHeader["name"] = GetMessage("SALE_".$arHeader["id"]);
		$arHeaders[] = $arHeader["id"];
	endforeach;
?>
<?
//pr();
?>
<section>
  <div class="table-responsive">
  <table class="table shopping-cart-table table-striped" id="basket_items">
	     <thead>
	       <tr>
	         <th style="width: 40%">Товар</th>
	         <th style="width: 13.5%">ЦЕНА</th>
	         <th style="width: 30%">Количество</th>
	         <th style="width: 13.5%">Итого</th>
	         <th style="width: 3%">&nbsp;</th>
	       </tr>
	     </thead>
	     <tbody>
		     <? foreach($arResult['ITEMS']['AnDelCanBuy'] as $keyProduct=>$arItem):?>

		     	<tr data-price="<?=$arItem['PRICE']?>" data-id="<?=$arItem['PRODUCT_ID']?>">
                  <td class="product-in-table">
                     <?
                     $detail_picture = false;
					 if ($arItem['DETAIL_PICTURE']):
						  	$detail_picture = $arItem['DETAIL_PICTURE'];
						  elseif($arItem['PREVIEW_PICTURE']):
						  	$detail_picture = $arItem['PREVIEW_PICTURE'];
					 endif;
                  ?>

                  	<? if ($detail_picture):?>
                  		<? $file = CFile::ResizeImageGet($detail_picture, array('width'=>200, 'height'=>200), BX_RESIZE_IMAGE_PROPORTIONAL, true); ?>
                  		<img class="img-responsive" src="<?=$file['src']?>" alt="">
                  		<?else:?>
                  		<img class="img-responsive" src="<?=$templateFolder?>/images/no_photo.png" alt="">
                  	<? endif?>

                    <div class="product-it-in">
										<!--<pre><?php print_r($arItem);?></pre>-->
                      <h3>
												<?=$arItem['NAME']?></h3>
                      <? if ($arItem['CATALOG']['PROPERTY_388_VALUE']):?>
                      	Артикул: <span><?=$arItem['CATALOG']['PROPERTY_388_VALUE'][0]?></span><br>
                      <? endif?>
                    </div>

                  </td>
                  <td>
				  <?=$arItem['PRICE_FORMATED']?>
				  <!--<? 
					$salePrice = $arItem['PROPERTY_MARKER_SALE_VALUE'];
					$price = $arItem['PRICE_FORMATED']
				  ?>
				  
				  <? if($price >=1){
						$price_r = $price-($price * $salePrice)/100;
						echo ($price_r).' руб.'; 
						
				  } else {
						$price_r = $arItem['PRICE_FORMATED'];
						echo $price_r;
				  }
				  ?>-->
				  </td>
                  <td style="white-space: normal !important">
                  	<div class="row center-block">
                  		<button type="button" class="quantity-button" name="subtract" onclick="subtractQtyMinus('QUANTITY_<?=$arItem['PRODUCT_ID']?>'); recalcBasketAjax();" value="-">-</button>
                  		<input type="text" class="quantity-field valid" name='QUANTITY_<?=$arItem['PRODUCT_ID']?>' data-id="<?=$arItem['PRODUCT_ID']?>" value="<?=($arItem['QUANTITY'] ? $arItem['QUANTITY'] : '0')?>" id='QUANTITY_<?=$arItem['PRODUCT_ID']?>' onchange="subtractQtyChange('QUANTITY_<?=$arItem['PRODUCT_ID']?>'); recalcBasketAjax();"">
                  		<button type="button" class="quantity-button" name="add" onclick="javascript: subtractQtyPluse('QUANTITY_<?=$arItem['PRODUCT_ID']?>'); recalcBasketAjax();" value="+">+</button>
                    </div></td>
                  <td class="price-column-summary"><?=CurrencyFormat($arItem['PRICE']*$arItem['QUANTITY'],$arItem['CURRENCY'])?></td>
                  <td><button type="button" class="close" data-id="<?=$arItem['PRODUCT_ID']?>" onclick="DeleteRowCart(this); recalcBasketAjax(this);"><span>&times;</span><span class="sr-only">Close</span></button></td>
                </tr>
		     <? endforeach;?>
	     </tbody>
     </table>
  </div>
    <input type="hidden" id="column_headers" value="<?=CUtil::JSEscape(implode($arHeaders, ","))?>" />
	<input type="hidden" id="offers_props" value="<?=CUtil::JSEscape(implode($arParams["OFFERS_PROPS"], ","))?>" />
	<input type="hidden" id="action_var" value="<?=CUtil::JSEscape($arParams["ACTION_VARIABLE"])?>" />
	<input type="hidden" id="quantity_float" value="<?=$arParams["QUANTITY_FLOAT"]?>" />
	<input type="hidden" id="count_discount_4_all_quantity" value="<?=($arParams["COUNT_DISCOUNT_4_ALL_QUANTITY"] == "Y") ? "Y" : "N"?>" />
	<input type="hidden" id="price_vat_show_value" value="<?=($arParams["PRICE_VAT_SHOW_VALUE"] == "Y") ? "Y" : "N"?>" />
	<input type="hidden" id="hide_coupon" value="<?=($arParams["HIDE_COUPON"] == "Y") ? "Y" : "N"?>" />
	<input type="hidden" id="coupon_approved" value="N" />
	<input type="hidden" id="use_prepayment" value="<?=($arParams["USE_PREPAYMENT"] == "Y") ? "Y" : "N"?>" />
</section>

<div class="coupon-code" id="coupons_block">
  <div class="row">
    <div class="col-sm-4 sm-margin-bottom-30">
    	<?php
    	$arGroupsDealer = CUser::GetUserGroup($USER->GetID());
    	if (in_array("19", $arGroupsDealer)) { ?>

		<?php } else { ?>
	    	<?
				if ($arParams["HIDE_COUPON"] != "Y"):
			?>
			      <h3>Скидка</h3>
			      <p>Введите код купона для скидки</p>
			      <input type="text" class="form-control margin-bottom-10" id="coupon" name="COUPON" value="">
			      <button type="button" class="btn-u btn-u-sea-shop" onclick="enterCoupon();">Готово</button>
		    <? endif?>
		<?php } ?>
    </div>

    <div class="col-sm-3 col-sm-offset-5">
      <ul class="list-inline total-result">
        <li>
          <h4>Сумма:</h4>
          <div class="total-result-in"> <span><?=$arResult['allSum_FORMATED']?></span> </div>
        </li>
        <li class="divider"></li>
        <li class="total-price">
          <h4>Всего:</h4>
          <div class="total-result-in"> <span><?=$arResult['allSum_FORMATED']?></span> </div>
        </li>
      </ul>
    </div>
  </div>
</div>
<?
else:
?>
<div id="basket_items_list">
	<table>
		<tbody>
			<tr>
				<td colspan="<?=$numCells?>" style="text-align:center">
					<div class=""><?=GetMessage("SALE_NO_ITEMS");?></div>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<?
endif;
?>