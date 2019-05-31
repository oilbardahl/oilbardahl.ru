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
<?if ($arResult["ITEMS"]):?>
	<?$indexRow =0; ?>
	<?foreach($arResult["ITEMS"] as $arItem):?>
		<?
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
		?>
	    <? if ($indexRow==0):?>
			<div class="row news-v2">
		<?endif?>
			<div class="col-md-4" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
		          <div class="item-block margin-bottom-30">
						<div class="news-v2-badge">
							<? if ($arItem['PREVIEW_PICTURE']):?>
									<img class="img-responsive" src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arItem['NAME']?>"/>
							<?endif?>
							<? if ($arItem['ACTIVE_FROM']):?>
								<?$dateFull = MakeTimeStamp($arItem['ACTIVE_FROM'], "DD.MM.YYYY HH:MI:SS");?>
								<p>
									<span><?=FormatDate('d',$dateFull);?></span>
									<small><?=FormatDate('M',$dateFull);?></small>
								</p>
							<? endif?>
						</div>
						<div class="news-v2-desc bg-color-light">
							<h3><a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME']?></a></h3>
							<p><?=$arItem['~PREVIEW_TEXT']?></p>
						</div>
				   </div>
		  	</div>
		  	<?$indexRow++;?>
		<? if ($indexRow>2):?>
			<?$indexRow=0?>
			</div>
		<?endif?>
	<? endforeach?>
	<? if ($indexRow>0):?>
	</div>
	<?endif?>
	<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
		<br /><?=$arResult["NAV_STRING"]?>
	<?endif;?>
<?endif?>
