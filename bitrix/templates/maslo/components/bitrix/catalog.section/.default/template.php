<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<?
if (!empty($arResult['ITEMS']))
{
	 ?>
	<div class="filter-results illustration-v2">
	<?$indexItem = 0; ?>
	<? $nf = 0; ?>
	<? foreach ($arResult['ITEMS'] as $key => $arItem){
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $strElementEdit);
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $strElementDelete, $arElementDeleteParams);
		$strMainID = $this->GetEditAreaId($arItem['ID']);
		?>
			<?if ($indexItem==0):?><div class="row"><?endif?>
        	<div class="col-md-4 col-sm-6" id="<?=$strMainID?>">
			
				<? if ($arItem['PROPERTIES']['FALL']['VALUE']){ ?>
					<div class="marker-circle-orange"></div>
				<? } ?>
			
         		<div class="item-block">
                   <div class="product-img">
                       <a href="<?=$arItem['DETAIL_PAGE_URL']?>">
                       	  <?
                       	  if ($arItem['PREVIEW_PICTURE']['ID']):
                       	  	$file = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE']['ID'], array('width'=>370, 'height'=>370), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                       	  ?>
                       	  	<img class="stack-images-list img-responsive" src="<?=$file['src']?>" alt="<?=$arItem['NAME']?>">
                       	  	<?else: ?>
                       	  	<img class="stack-images-list img-responsive" src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arItem['NAME']?>">
                       	  <?endif?>
                       </a>
                   </div>
                   <div class="product-description product-description-brd cat-l-d">
                       <div class="overflow-h margin-bottom-15">
                           <div class="pull-left">
                               <h4 class="title-price"><a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=TruncateText($arItem['NAME'], 100)?></a></h4>
                               <? /*
							   <span class="gender"><?=TruncateText(strip_tags($arItem['~PREVIEW_TEXT']),40)?></span>
							   */ ?>
							   <span class="gender"><?= $arItem['PREVIEW_TEXT']; ?></span>
                           </div>
                       </div>

                        <?$ratingVisible = false;?>
						<? if ($arItem['PROPERTIES']['vote_sum']['VALUE'] && $arItem['PROPERTIES']['vote_count']['VALUE']):?>
							<?$ratingVisible = floor($arItem['PROPERTIES']['vote_sum']['VALUE']/$arItem['PROPERTIES']['vote_count']['VALUE'])?>
						<? endif?>
						<ul class="list-inline product-ratings margin-bottom-15">
							<? if ($ratingVisible):?>
								<? for($i=0;$i<5;$i++):?>
									<li><i class="<?if ($i<$ratingVisible):?>rating-selected<?else:?>rating<?endif?> fa fa-star"></i></li>
								<? endfor?>
								<?else:?>
									<li><i class="rating fa fa-star"></i></li>
									<li><i class="rating fa fa-star"></i></li>
									<li><i class="rating fa fa-star"></i></li>
									<li><i class="rating fa fa-star"></i></li>
									<li><i class="rating fa fa-star"></i></li>
							<? endif?>
						</ul><!--/end shop product ratings-->
                   </div>
                   <div class="product-price-block cat-l">
                       <div class="overflow-h">
                       		<?  
								$price=$arItem['MIN_PRICE']['VALUE'];
								$sale=$arItem['PROPERTIES']['MARKER_SALE']['VALUE'];
								$nx =  round($price/((100 - $sale)/100));
								$sale_nx =  round($price - $nx);
							?>
							
							<?	if (($sale > 0) and ($sale <= 5)) { ?>
									<div class="bx_catalog_item_price_sale1 sale_catalog">
										<? echo $sale_nx.' руб.'; ?>
									</div>
									<div class="col-xs-6 product-price">
										<span class="title-price sale-style"><?=$arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE_VAT']?></span>
										<div class="sale-price">
											<? echo $nx.' руб.'; ?>
										</div>
									</div>
							<?	} elseif (($sale > 5) and ($sale < 10)) { ?>
									<div class="bx_catalog_item_price_sale2 sale_catalog">
										<? echo $sale_nx.' руб.'; ?>
									</div>
									<div class="col-xs-6 product-price">
										<span class="title-price sale-style"><?=$arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE_VAT']?></span>
										<div class="sale-price">
											<? echo $nx.' руб.'; ?>
										</div>
									</div>
							<?	} elseif ($sale >= 10) { ?>
									<div class="bx_catalog_item_price_sale3 sale_catalog">
										<? echo $sale_nx.' руб.'; ?>
									</div>
									<div class="col-xs-6 product-price">
										<span class="title-price sale-style"><?=$arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE_VAT']?></span>
										<div class="sale-price">
											<? echo $nx.' руб.'; ?>
										</div>
									</div>
							<?	} else { ?>
									<div class="col-xs-6 product-price">
										<span class="title-price"><?=$arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE_VAT']?></span>
									</div>
							<?	}
							?>
	                        <div class="col-xs-6">
	                        	<a href="<?=$arItem['ADD_URL']?>" class="price-block-btn pull-right" onclick="addToBasketMod(this); return false;">В корзину <i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
	                        </div>
                       </div>
                   </div>

           		</div>
           </div>
           <?$indexItem++;?>
           <?if ($indexItem>3):?></div><? $nf++; ?><?$indexItem=0;?>
		   <?endif?>
		   
	<? } ?>
		<?if ($indexItem>0):?>
			</div>
		<?endif?>
		
	
 	</div>
	
	
	
	<? if ($arParams["DISPLAY_BOTTOM_PAGER"])
		{
			?><? echo $arResult["NAV_STRING"]; ?>
			<?
		}
} else {
?>
<div class="filter-results illustration-v2">
Не найдено ни одного товара.
</div>
<?}?>

	<?php if (!isset($_GET["PAGEN_1"])) { ?>
	<div class="cat-description">
	<?=	$arResult["DESCRIPTION"] ?>
	</div>
	<?php } ?>