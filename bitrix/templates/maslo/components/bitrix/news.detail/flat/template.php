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
<div class="margin-bottom-20">
  <?if (is_array($arResult["DETAIL_PICTURE"])):?>
	<img class="img-responsive"
		src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>"
		width="<?=$arResult["DETAIL_PICTURE"]["WIDTH"]?>"
		height="<?=$arResult["DETAIL_PICTURE"]["HEIGHT"]?>"
		alt="<?=$arResult["DETAIL_PICTURE"]["ALT"]?>"
		title="<?=$arResult["DETAIL_PICTURE"]["TITLE"]?>"
		/>
  <?endif;?>
  <?if ($arResult['ACTIVE_FROM']):?>
  	<h3><?=FormatDate("d F Y", MakeTimeStamp($arResult['ACTIVE_FROM']))?></h3>
  <?endif?>
  <h1><?=$arResult["NAME"]?></h1>
  <?if(strlen($arResult["DETAIL_TEXT"])>0):?>
		<p><?echo $arResult["DETAIL_TEXT"];?></p>
	<?else:?>
		<p><?echo $arResult["PREVIEW_TEXT"];?></p>
  <?endif?>
</div>