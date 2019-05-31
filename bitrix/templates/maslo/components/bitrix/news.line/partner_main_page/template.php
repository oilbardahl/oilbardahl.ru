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
<? if ($arResult["ITEMS"]):?>
<!--=== Sponsors ===-->
    <div class="bg-yellow">
		<div class="container content">
			<div class="heading heading-v4 margin-bottom-40">
				<h2>оюпрмепш <strong>BARDAHL</strong></h2>
			</div>
			<ul class="list-inline owl-slider-v2">
				<?foreach($arResult["ITEMS"] as $arItem):?>
					<?
						$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
						$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
					?>
					<? if ($arItem['PREVIEW_PICTURE']):?>
						<li class="item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
							<a href="<?=$arItem['PROPERTY_LINK_TO_PAGE_VALUE']?>">
								<img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arItem['NAME']?>"/>
						    </a>
						</li>
					<?endif?>
				<? endforeach;?>
			</ul><!--/end owl-carousel-->
		</div>
    </div>
<!--=== End Sponsors ===-->
<? endif?>