<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */
$this->SetViewTarget("smart_recomend");
$this->setFrameMode(true);
$intRowsCount = count($arResult['ITEMS']);
?>
<? if ($intRowsCount>0):?>
	<div class="bg-yellow">
		   <div class="container content">
			    <div class="heading heading-v4 margin-bottom-20">
					<h2>C ›“»Ã “Œ¬¿–ŒÃ “¿  ∆≈ œŒ ”œ¿ﬁ“</h2>
				</div>
				<!--=== Illustration v2 ===-->
				<div class="illustration-v2">

					<ul class="list-inline owl-slider owl-carousel owl-theme">
					<?
					foreach ($arResult['ITEMS'] as $keyRow => $arItem){
						?>
	                   		<li class="item">
								<div class="product-img">
									<a href="<?=$arItem['DETAIL_PAGE_URL']?>">
										<? if ($arItem['PREVIEW_PICTURE']):?>
												<? $file = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE']['ID'], array('width'=>370, 'height'=>370), BX_RESIZE_IMAGE_PROPORTIONAL, true); ?>
												<img class="full-width img-responsive" src="<?=$file['src']?>" alt="<?=$arItem['NAME']?>" title="<?=$arItem['NAME']?>"/>
											<?elseif($arItem['DETAIL_PICTURE']):?>
												<? $file = CFile::ResizeImageGet($arItem['DETAIL_PICTURE']['ID'], array('width'=>370, 'height'=>370), BX_RESIZE_IMAGE_PROPORTIONAL, true); ?>
												<img class="full-width img-responsive" src="<?=$file['src']?>" alt="<?=$arItem['NAME']?>" title="<?=$arItem['NAME']?>"/>
										<? endif?>
									</a>
									<? if ($arItem['PROPERTIES']['NEWPRODUCT']['VALUE']):?>
				                    		<div class="shop-bg-red rgba-banner">ÕÓ‚ËÌÍ‡</div>
				                    	<?elseif($arItem['PROPERTIES']['SALELEADER']['VALUE']):?>
				                    	   <div class="shop-bg-green rgba-banner">’ËÚ ÔÓ‰‡Ê</div>
				                    <? endif?>

								</div>
								<div class="product-description product-description-brd">
									<div class="overflow-h margin-bottom-5">
										<div class="pull-left">
											<h4 class="title-price"><a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=TruncateText($arItem['NAME'], 100)?></a></h4>
											<span class="gender">
												<?
												$res = CIBlockElement::GetByID($arItem["ID"]);
												if($ar_res = $res->GetNext()):
												?>
													<? if ($arItem['PREVIEW_TEXT']):?>
                                                    <?=TruncateText(strip_tags($arItem['~PREVIEW_TEXT']), 40)?>
                                                <?endif?>
												<? endif?>
											</span>
										</div>
									</div>
								</div>
								<div class="product-price-block">
									<div class="overflow-h">
										<div class="product-price">
											<span class="title-price"><?=$arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE']?></span>
										</div>
									</div>
								</div>
							</li>
						<?}
					?>
					</ul>
					<div class="customNavigation margin-bottom-25">
						<a class="owl-btn prev rounded-x"><i class="fa fa-angle-left"></i></a>
						<a class="owl-btn next rounded-x"><i class="fa fa-angle-right"></i></a>
					</div>
				</div>
			</div>
	</div>
<?endif?>

<?
$this->EndViewTarget();
?>
