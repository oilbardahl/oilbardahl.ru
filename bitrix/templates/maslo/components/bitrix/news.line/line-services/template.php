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
	<div class="bg-yellow">
		<div class="container">
			<!--=== Product Service ===-->
			<div class="row content">
				<?foreach($arResult["ITEMS"] as $arItem):?>
					<?
					$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
					$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
					?>
					<?
						$classXML = '';
						$db_props = CIBlockElement::GetProperty($arItem['IBLOCK_ID'], $arItem['ID'], array("sort" => "asc"), Array("CODE"=>"TYPE_ICON"));
						if($ar_props = $db_props->Fetch()):
						   $classXML = $ar_props['VALUE_XML_ID'];
						endif;
					?>
					<div class="col-md-4 product-service md-margin-bottom-30" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
						<div class="shadow_block">
							<div class="product-service-heading">
								<i class="<?=$classXML?>"></i>
							</div>
							<div class="product-service-in">
								<h3><?=$arItem['~NAME']?></h3>
								<p><?=$arItem['~PREVIEW_TEXT']?></p>
								<? if ($arItem['PROPERTY_LINK_TO_PAGE_VALUE']):?>
									<a href="<?=$arItem['PROPERTY_LINK_TO_PAGE_VALUE']?>">Подробнее</a>
								<? endif?>
							</div>
						</div>
					</div>
				<? endforeach;?>
			</div><!--/end row-->
			<!--=== End Product Service ===-->
		</div>
	</div>
<? endif?>