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
$intRowsCount = count($arResult['ITEMS']);
?>

<? if ($intRowsCount>0):?>
	<div class="bg-yellow">
		   <div class="container content">
			    <div class="heading heading-v4 margin-bottom-20">
					<h2>Популярные товары от <strong>BARDAHL</strong></h2>
				</div>
				<!--=== Illustration v2 ===-->
				<div class="illustration-v2">
					<?
					foreach ($arResult['ITEMS'] as $keyRow => $arOneRow){
						?>
						<div class="row">
						<?
						foreach ($arOneRow as $keyItem => $arItem){
						?>
							<?
							$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $strElementEdit);
							$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $strElementDelete, $arElementDeleteParams);
							$strMainID = $this->GetEditAreaId($arItem['ID']);
							?>
								<div class="col-md-3 col-sm-6" id="<? echo $strMainID; ?>">
		                   			<div class="item-block">
			                   			<div class="product-img">
											<a href="<?=$arItem['DETAIL_PAGE_URL']?>">
												<? if ($arItem['PREVIEW_PICTURE']):?>
														<img class="stack-images-list img-responsive" src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arItem['NAME']?>" title="<?=$arItem['NAME']?>"/>
													<?elseif($arItem['DETAIL_PICTURE']):?>
														<img class="stack-images-list img-responsive" src="<?=$arItem['DETAIL_PICTURE']['SRC']?>" alt="<?=$arItem['NAME']?>" title="<?=$arItem['NAME']?>"/>
												<? endif?>
											</a>
										</div>
										<div class="product-description product-description-brd">
											<div class="overflow-h margin-bottom-5">
												<div class="pull-left">
													<h4 class="title-price"><a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=TruncateText($arItem['NAME'], 100)?></a></h4>
													<span class="gender">
														<? if ($arItem['PREVIEW_TEXT']):?>
															<?=TruncateText(strip_tags($arItem['~PREVIEW_TEXT']), 40)?>
														<?endif?>
													</span>
												</div>
											</div>
										</div>
										<div class="product-price-block margin-bottom-30">
					                       <div class="overflow-h">
					                       		<div class="col-xs-6 product-price">
													<span class="title-price"><?=$arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE_VAT']?></span>
												</div>
						                        <div class="col-xs-6">
						                        	<a href="<?=$arItem['ADD_URL']?>" class="price-block-btn pull-right" onclick="addToBasketMod(this); return false;">В корзину <i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
						                        </div>
					                       </div>
					                   </div>
									</div>
								</div>
							<?} ?>
						</div>
					<?	} ?>
				</div>
			</div>
	</div>
<?endif?>