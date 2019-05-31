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

<? if (($arResult["ITEMS"]) and (count($arResult) > 2)):?>
	<!--=== Illustration v1 ===-->
	<div class="container">
		<div class="row content-xs">
			<?foreach($arResult["ITEMS"] as $keyIndex=>$arItem):?>
			<? if (trim($arItem["PREVIEW_PICTURE"]["SRC"]) != ""):?>
				<?
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				?>
				<div class="col-md-12" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
					<div class="overflow-h">
						<? if ($arItem['PROPERTY_LINK_TO_PAGE_VALUE'] && empty($arItem['PROPERTY_BUY_TO_PAGE_VALUE'])):?>
								<a href="<?=$arItem['PROPERTY_LINK_TO_PAGE_VALUE']?>" class="illustration-v1 illustration-img<?=($keyIndex+1)?>">
									<span class="illustration-bg">
										<span class="illustration-ads ad-details-v2">
										<img src="<?= $arItem["PREVIEW_PICTURE"]["SRC"]; ?>" />
										<!--
											<? if ($arItem['PREVIEW_TEXT']):?>
												<span class="item-time"><?=toUpper($arItem['PREVIEW_TEXT'])?></span>
											<?endif;?>
											<span class="item-name"><?=toUpper($arItem['NAME'])?></span>
										-->
										</span>
									</span>
								</a>
							<?else:?>
								<div class="illustration-v1 illustration-img<?=($keyIndex+1)?>">
									<div class="illustration-bg">
										<div class="illustration-ads ad-details-v1">
											<h3><?=toUpper($arItem['NAME'])?></h3>
											<? if ($arItem['PROPERTY_BUY_TO_PAGE_VALUE'] && $arItem['PROPERTY_LINK_TO_PAGE_VALUE']):?>
												<a class="btn-u btn-brd btn-brd-hover btn-u-light" href="<?=$arItem['PROPERTY_LINK_TO_PAGE_VALUE']?>"><?=$arItem['PROPERTY_BUY_TO_PAGE_VALUE']?></a>
											<?endif?>
										</div>
									</div>
								</div>
						<? endif?>
					</div>
				</div>
			<? endif?>
			<? endforeach?>
       </div>
	</div><!--/end row-->
	<!--=== End Illustration v1 ===-->
<? endif?>