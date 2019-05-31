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
<script type="text/javascript" src="/buyme/js/buyme.js"></script>
<!--[if IE]>
<link href="/buyme/templates/default/style.css" rel="stylesheet" type="text/css"/>
<![endif]-->

<div class="shop-product content">
	<div class="container">
		<div class="row">
			<div class="col-md-6 md-margin-bottom-50">
				
				<? if ($arResult['PROPERTIES']['FALL']['VALUE']){ ?>
					<div class="marker-circle-orange-element"></div>
				<? } ?>
				
				<? if ($arResult['DETAIL_PICTURE']):?>
					<? $file = CFile::ResizeImageGet($arResult['DETAIL_PICTURE']['ID'], array('width'=>555, 'height'=>555), BX_RESIZE_IMAGE_PROPORTIONAL, true); ?>
   					<img src="<?=$file['src']?>" class="img-responsive center-block">
			    <? elseif($arResult['PREVIEW_PICTURE']):?>
			    	<? $file = CFile::ResizeImageGet($arResult['PREVIEW_PICTURE']['ID'], array('width'=>555, 'height'=>555), BX_RESIZE_IMAGE_PROPORTIONAL, true); ?>
  					<img src="<?=$file['src']?>" class="img-responsive center-block">
			    <? else:?>
			    	<img src="<?=$templateFolder?>/images/no_photo.png" class="img-responsive center-block">
			    <? endif?>
			</div>

			<div class="col-md-6">
				<div class="shop-product-heading">
					<h2 class="b1c-name"><?=$arResult['NAME']?></h2>
				</div>
                <?$APPLICATION->IncludeComponent("custom:iblock.vote", "stars", Array(
					"IBLOCK_TYPE" => "catalog",	// Тип инфоблока
						"IBLOCK_ID" => $arParams["IBLOCK_ID"],	// Инфоблок
						"ELEMENT_ID" => $arResult["ID"],	// ID элемента
						"ELEMENT_CODE" => "",	// Код элемента
						"MAX_VOTE" => "5",	// Максимальный балл
						"VOTE_NAMES" => "",	// Подписи к баллам
						"SET_STATUS_404" => "N",	// Устанавливать статус 404
						"MESSAGE_404" => "",	// Сообщение для показа (по умолчанию из компонента)
						"CACHE_TYPE" => "N",	// Тип кеширования
						"CACHE_TIME" => "3600",	// Время кеширования (сек.)
						"COMPONENT_TEMPLATE" => "stars",
						"DISPLAY_AS_RATING" => "rating",	// В качестве рейтинга показывать
					),
					false
				);?>
                <? if ($arResult['~DETAIL_TEXT']):?>
						<p class="margin-bottom-15"><?=$arResult['~DETAIL_TEXT']?></p>
					<?elseif($arResult['~PREVIEW_TEXT']):?>
						<p class="margin-bottom-15"><?=$arResult['~PREVIEW_TEXT']?></p>
                <? endif;?>

				
                <? if ($arResult['PROPERTIES']['ARTICLE']['VALUE'] && $arResult['PROPERTIES']['CHARACTERS']['VALUE']):?>
                		<p class="wishlist-category"><strong><?=$arResult['PROPERTIES']['ARTICLE']['NAME']?>:</strong> <?=$arResult['PROPERTIES']['ARTICLE']['VALUE']?></p>
                	<?elseif($arResult['PROPERTIES']['ARTICLE']['VALUE']):?>
                		<p class="wishlist-category"><strong><?=$arResult['PROPERTIES']['ARTICLE']['NAME']?>:</strong> <?=$arResult['PROPERTIES']['ARTICLE']['VALUE']?></p>
                <? endif;?>
				
				<!-- Свойство литраж -->
				<? if (!empty($arResult['PROPERTIES']['LITER']['VALUE'])){ ?>
					<p class="wishlist-category"><strong><?=$arResult['PROPERTIES']['LITER']['NAME']?>:</strong> <?=$arResult['PROPERTIES']['LITER']['VALUE']?></p>
				<? }; ?>
				
				<!-- Свойство группа товара -->
				<? if (!empty($arResult['PROPERTIES']['GROUP_PRODUCT']['VALUE'])){ ?>
					<p class="wishlist-category"><strong><?=$arResult['PROPERTIES']['GROUP_PRODUCT']['NAME']?>:</strong> <?=$arResult['PROPERTIES']['GROUP_PRODUCT']['VALUE']?></p>
				<? }; ?>
				
				<!-- Свойство количество в упаковке -->
				<? if (!empty($arResult['PROPERTIES']['AMOUNT_BACK']['VALUE'])){ ?>
					<p class="wishlist-category"><strong><?=$arResult['PROPERTIES']['AMOUNT_BACK']['NAME']?>:</strong> <?=$arResult['PROPERTIES']['AMOUNT_BACK']['VALUE']?></p>
				<? }; ?>
				
                <? if ($arResult['PROPERTIES']['CHARACTERS']['VALUE'] && $arResult['PROPERTIES']['CHARACTERS']['DESCRIPTION']):?>
           			<? foreach($arResult['PROPERTIES']['CHARACTERS']['VALUE'] as $keyP=>$valP):?>
           				<? if($arResult['PROPERTIES']['CHARACTERS']['DESCRIPTION'][$keyP]):?>
           					<p class="wishlist-category <?if ($keyP==count($arResult['PROPERTIES']['CHARACTERS']['VALUE'])-1):?>margin-bottom-30<?endif?>"><strong><?=$valP?>:</strong> <?=$arResult['PROPERTIES']['CHARACTERS']['DESCRIPTION'][$keyP]?></p>
           				<? endif?>
           			<? endforeach?>
              	<? endif?>
				
				<!-- Старая цена, расчитана по маркеру -->	
				<? if (!empty($arResult['PROPERTIES']['MARKER_SALE']['VALUE'])){ ?>
				<? $price=$arResult['MIN_PRICE']['VALUE'];
					$sale=$arResult['PROPERTIES']['MARKER_SALE']['VALUE'];
					$nx =  round($price/((100 - $sale)/100));
				?>
					<p class="wishlist-category sale-price"><? echo $nx.' руб.'; ?></p>
				<? }; ?>
				
				<? if ($arResult['MIN_PRICE']):?>
	              	<ul class="list-inline shop-product-prices margin-bottom-30">
	              		<? if ($arResult['MIN_PRICE']['VALUE']>$arResult['MIN_PRICE']['DISCOUNT_VALUE']):?>
								<li class="shop-red"><?=$arResult['MIN_PRICE']['PRINT_DISCOUNT_VALUE']?></li>
								<li class="line-through"><?=$arResult['MIN_PRICE']['PRINT_VALUE']?></li>
							<?else:?>
								<li><?=$arResult['MIN_PRICE']['PRINT_VALUE']?></li>
						<? endif?>
					</ul><!--/end shop product prices-->
                    <? if($arResult['CATALOG_QUANTITY']>0):?>
							<h3 class="shop-product-title">Количество</h3>
							<div class="margin-bottom-10 mobile-500" data-id="<?=$arResult['ID']?>">
								<form name="f1" class="product-quantity sm-margin-bottom-20">
									<button type="button" class="quantity-button" name="subtract" onclick="minusQty(this);" value="-">-</button>
									<input type="text" class="quantity-field" name="qty" value="1" id="qty">
									<button type="button" class="quantity-button" name="add" onclick="pluseQty(this);" value="+">+</button>
								</form>
								<button type="button" class="btn-u btn-u-sea-shop btn-u-lg" data-id="<?=$arResult['ID']?>" onclick="addToBasket(this);">В корзину</button>
								
								<input type="button" value="Купить в 1 клик" class="b1c">
								
							</div><!--/end product quantity-->
						<?else:?>
							<h3 class="shop-product-title">Нет в наличии</h3>
					<?endif?>
                <?endif?>

			</div>
		</div><!--/end row-->
	</div>
</div>
<?if ($arResult['PROPERTIES']['LINK_YOUTUBE']['VALUE'] || $arResult['PROPERTIES']['OPTIONS_TEXT']['~VALUE']['TEXT'] || $arResult['PROPERTIES']['SPEC_LIST']['VALUE'] || $arResult['PROPERTIES']['SHOW_LINK_FORMULA_FULL']['VALUE'] || $arResult['PROPERTIES']['SHOW_LINK_FORMULA_POL']['VALUE']):?>
	<!--=== Content Medium ===-->
	<div class="bg-yellow">
			<div class="content container">
				<div class="row">
					<? if ($arResult['PROPERTIES']['LINK_YOUTUBE']['VALUE'] && ($arResult['PROPERTIES']['OPTIONS_TEXT']['~VALUE']['TEXT'] || $arResult['PROPERTIES']['SPEC_LIST']['VALUE'] || $arResult['PROPERTIES']['SHOW_LINK_FORMULA_FULL']['VALUE'] || $arResult['PROPERTIES']['SHOW_LINK_FORMULA_POL']['VALUE'])):?>
						<div class="col-md-7">
							<? if ($arResult['PROPERTIES']['OPTIONS_TEXT']['~VALUE']['TEXT']):?>
				    			<div class="shop-product-heading">
									<h2>Свойства</h2>
								</div>
								<p><?=$arResult['PROPERTIES']['OPTIONS_TEXT']['~VALUE']['TEXT']?></p>
							<?endif?>

							<? if ($arResult['PROPERTIES']['SPEC_LIST']['VALUE'] || ($arResult['PROPERTIES']['SHOW_LINK_FORMULA_FULL']['VALUE'] || $arResult['PROPERTIES']['SHOW_LINK_FORMULA_POL']['VALUE'])):?>
								<h3 class="heading-md margin-bottom-10">Спецификации</h3>
		                        <div class="row">
		                        	<? if ($arResult['PROPERTIES']['SPEC_LIST']['VALUE']):?>
				                         <div class="col-sm-8">
											<ul class="list-unstyled specifies-list">
												<? foreach($arResult['PROPERTIES']['SPEC_LIST']['~VALUE'] as $aVal):?>
													<li><i class="fa fa-caret-right"></i><?=$aVal?></li>
												<? endforeach;?>
											</ul>
										</div>
									<? endif?>

									<? if ($arResult['PROPERTIES']['SHOW_LINK_FORMULA_FULL']['VALUE'] || $arResult['PROPERTIES']['SHOW_LINK_FORMULA_POL']['VALUE']):?>
										<div class="col-sm-4">
											<? if ($arResult['PROPERTIES']['SHOW_LINK_FORMULA_FULL']['VALUE_XML_ID']):?>
		                            			<div class="col-xs-6">
		                            				<a href="<?=$arResult['PROPERTIES']['SHOW_LINK_FORMULA_FULL']['VALUE_XML_ID']?>">
		                            					<img class="img-responsive" src="<?=SITE_TEMPLATE_PATH?>/assets/img/formula-logo-1.png">
		                            				</a>
		                            			</div>
	                            			<? endif?>
	                            			<? if ($arResult['PROPERTIES']['SHOW_LINK_FORMULA_POL']['VALUE_XML_ID']):?>
		                            			<div class="col-xs-6">
		                            				<a href="<?=$arResult['PROPERTIES']['SHOW_LINK_FORMULA_POL']['VALUE_XML_ID']?>">
		                            					<img class="img-responsive" src="<?=SITE_TEMPLATE_PATH?>/assets/img/formula-logo-2.png">
		                            				</a>
		                            			</div>
		                            		<? endif?>
	                            		</div>
                            		<? endif?>
		                        </div>
		                    <?endif?>
		       			</div>
		       			<?$arVideo = explode('/',$arResult['PROPERTIES']['LINK_YOUTUBE']['VALUE']);?>
						<div class="col-md-5">
		    				<div class="shop-product-heading">
								<h2>Видео</h2>
							</div>
							<div class="responsive-video">
								<iframe width="100%" src="https://www.youtube.com/embed/<?=$arVideo[count($arVideo)-1]?>" frameborder="0" allowfullscreen=""></iframe>
							</div>
		     			</div>
		     			<?elseif($arResult['PROPERTIES']['LINK_YOUTUBE']['VALUE']):?>
		     				<?$arVideo = explode('/',$arResult['PROPERTIES']['LINK_YOUTUBE']['VALUE']);?>
		    				<div class="col-md-12">
			    				<div class="shop-product-heading">
									<h2>Видео</h2>
								</div>
								<div class="responsive-video">
									<iframe width="100%" src="http://www.youtube.com/embed/<?=$arVideo[count($arVideo)-1]?>" frameborder="0" allowfullscreen=""></iframe>
								</div>
							</div>
						<?elseif(($arResult['PROPERTIES']['OPTIONS_TEXT']['~VALUE']['TEXT'] || $arResult['PROPERTIES']['SPEC_LIST']['VALUE'] || $arResult['PROPERTIES']['SHOW_LINK_FORMULA_FULL']['VALUE'] || $arResult['PROPERTIES']['SHOW_LINK_FORMULA_POL']['VALUE'])):?>
							<div class="col-md-12">
								<? if ($arResult['PROPERTIES']['OPTIONS_TEXT']['~VALUE']['TEXT']):?>
					    			<div class="shop-product-heading">
										<h2>Свойства</h2>
									</div>
									<p><?=$arResult['PROPERTIES']['OPTIONS_TEXT']['~VALUE']['TEXT']?></p>
								<?endif?>

								<? if ($arResult['PROPERTIES']['SPEC_LIST']['VALUE'] || $arResult['PROPERTIES']['SHOW_LINK_FORMULA_FULL']['VALUE'] || $arResult['PROPERTIES']['SHOW_LINK_FORMULA_POL']['VALUE'] ):?>
									<h3 class="heading-md margin-bottom-10">Спецификации</h3>
			                        <div class="row">
				                         <? if ($arResult['PROPERTIES']['SPEC_LIST']['VALUE']):?>
				                         	<div class="col-sm-8">
													<ul class="list-unstyled specifies-list">
														<? foreach($arResult['PROPERTIES']['SPEC_LIST']['~VALUE'] as $aVal):?>
															<li>
															<?php
																														
																$str = $aVal;
																$mass = explode('###',$str);
																
																foreach($mass as $n){
																	if (trim($n) != ""){
																		echo '<i class="fa fa-caret-right"></i>'.$n.'<br />';
																	}
																}
																
															?></li>
														<? endforeach;?>
													</ul>
												</div>
											<? endif?>

											<? if ($arResult['PROPERTIES']['SHOW_LINK_FORMULA_FULL']['VALUE'] || $arResult['PROPERTIES']['SHOW_LINK_FORMULA_POL']['VALUE']):?>
												<div class="col-sm-4">
													<? if ($arResult['PROPERTIES']['SHOW_LINK_FORMULA_FULL']['VALUE_XML_ID']):?>
				                            			<div class="col-xs-6">
				                            				<a href="<?=$arResult['PROPERTIES']['SHOW_LINK_FORMULA_FULL']['VALUE_XML_ID']?>">
				                            					<img class="img-responsive" src="<?=SITE_TEMPLATE_PATH?>/assets/img/formula-logo-1.png">
				                            				</a>
				                            			</div>
			                            			<? endif?>
			                            			<? if ($arResult['PROPERTIES']['SHOW_LINK_FORMULA_POL']['VALUE_XML_ID']):?>
				                            			<div class="col-xs-6">
				                            				<a href="<?=$arResult['PROPERTIES']['SHOW_LINK_FORMULA_POL']['VALUE_XML_ID']?>">
				                            					<img class="img-responsive" src="<?=SITE_TEMPLATE_PATH?>/assets/img/formula-logo-2.png">
				                            				</a>
				                            			</div>
				                            		<? endif?>
			                            		</div>
		                            		<? endif?>
			                        </div>
			                    <?endif?>
							</div>
	     			<? endif?>
				</div>
				
				<div class="row" id="comments-block">
				<div class="col-md-12">
				<h2>Отзывы о товаре</h2>
				<?//отзывы о товарах
				$APPLICATION->IncludeComponent(
	"bitrix:catalog.comments", 
	"product-comments", 
	array(
		"TEMPLATE_THEME" => "blue",
		"IBLOCK_TYPE" => "catalog",
		"IBLOCK_ID" => "2",
		"ELEMENT_ID" => $arResult["ID"],
		"ELEMENT_CODE" => "",
		"URL_TO_COMMENT" => $arResult["DETAIL_PAGE_URL"],
		"WIDTH" => "",
		"COMMENTS_COUNT" => "5",
		"BLOG_USE" => "Y",
		"FB_USE" => "N",
		"VK_USE" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "0",
		"BLOG_TITLE" => "Отзывы о товаре",
		"BLOG_URL" => "catalog_comments",
		"PATH_TO_SMILE" => "/bitrix/images/blog/smile/",
		"EMAIL_NOTIFY" => "Y",
		"AJAX_POST" => "Y",
		"SHOW_SPAM" => "Y",
		"SHOW_RATING" => "N",
		"RATING_TYPE" => "like_graphic",
		"FB_TITLE" => "Facebook",
		"FB_USER_ADMIN_ID" => "",
		"FB_APP_ID" => "",
		"FB_COLORSCHEME" => "dark",
		"FB_ORDER_BY" => "time",
		"VK_TITLE" => "Вконтакте",
		"VK_API_ID" => "API_ID",
		"COMPONENT_TEMPLATE" => "product-comments",
		"SHOW_DEACTIVATED" => "N",
		"CHECK_DATES" => "Y",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>
				<br />
				<?//с этим товаром покупают
				/*$APPLICATION->IncludeComponent("bitrix:sale.recommended.products","products",
				Array(
						"IBLOCK_TYPE" => "catalog",
						"IBLOCK_ID" => "2",
						"ID" => $arResult["ID"],
						"CODE" => $arResult["CODE"],
						"MIN_BUYES" => "1",
						"HIDE_NOT_AVAILABLE" => "N",
						"SHOW_DISCOUNT_PERCENT" => "N",
						"PRODUCT_SUBSCRIPTION" => "N",
						"SHOW_NAME" => "Y",
						"SHOW_IMAGE" => "Y",
						"MESS_BTN_BUY" => "Купить",
						"MESS_BTN_DETAIL" => "Подробнее",
						"MESS_NOT_AVAILABLE" => "Нет в наличии",
						"MESS_BTN_SUBSCRIBE" => "Подписаться",
						"PAGE_ELEMENT_COUNT" => "5",
						"LINE_ELEMENT_COUNT" => "5",
						"TEMPLATE_THEME" => "blue",
						"DETAIL_URL" => "",
						"CACHE_TYPE" => "A",
						"CACHE_TIME" => "86400",
						"SHOW_OLD_PRICE" => "N",
						"PRICE_CODE" => array("BASE"),
						"SHOW_PRICE_COUNT" => "1",
						"PRICE_VAT_INCLUDE" => "Y",
						"CONVERT_CURRENCY" => "N",
						"BASKET_URL" => "/personal/basket.php",
						"ACTION_VARIABLE" => "action",
						"PRODUCT_ID_VARIABLE" => "id",
						"PRODUCT_QUANTITY_VARIABLE" => "quantity",
						"ADD_PROPERTIES_TO_BASKET" => "Y",
						"PRODUCT_PROPS_VARIABLE" => "prop",
						"PARTIAL_PRODUCT_PROPERTIES" => "N",
						"USE_PRODUCT_QUANTITY" => "Y",
						"SHOW_PRODUCTS_6" => "Y",
						"PROPERTY_CODE_6" => array("YEAR", "AUTHORS", ""),
						"CART_PROPERTIES_6" => array("AUTHORS", ""),
						"ADDITIONAL_PICT_PROP_6" => "MORE_PHOTO",
						"LABEL_PROP_6" => "NEW_BOOK",
						"PROPERTY_CODE_20" => array(""),
						"CART_PROPERTIES_20" => array(""),
						"ADDITIONAL_PICT_PROP_20" => "FILE",
						"OFFER_TREE_PROPS_20" => array()
					)
				);*/?>
				<br />
				<? // последние просмотренные товары
				  $APPLICATION->IncludeComponent("bitrix:sale.viewed.product", "products", array(
					 "VIEWED_COUNT" => "5", 
					 "VIEWED_NAME" => "Y", 
					 "VIEWED_IMAGE" => "Y", 
					 "VIEWED_PRICE" => "Y", 
					 "VIEWED_CANBUY" => "Y",
					 "VIEWED_CANBUSKET" => "Y",
					 "VIEWED_IMG_HEIGHT" => 150,
					 "VIEWED_IMG_WIDTH" => 150,
					 "BASKET_URL" => "/personal/cart",
					 "ACTION_VARIABLE" => "action",
					 "PRODUCT_ID_VARIABLE" => "id"
					 )
				  );
				?>

				</div>
				
				
				
				</div>
			</div>
			
			
			<style>
			.row {
			margin-right: 0px;
			margin-left: 0px;
			}
			</style>
			
			
			
			
	</div><!--/end container-->
	<!--=== End Content Medium ===-->
<?endif?>