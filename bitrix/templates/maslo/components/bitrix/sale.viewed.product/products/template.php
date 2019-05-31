<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>
<div class="view-list">
	<h2><?=GetMessage("VIEW_HEADER");?><h2>
	<?foreach($arResult as $arItem):?>
		<div class="view-item">
			<?if($arParams["VIEWED_IMAGE"]=="Y" && is_array($arItem["PICTURE"])):?>
				<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><img src="<?=$arItem["PICTURE"]["src"]?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>"></a>
			<?endif?>
			<?if($arParams["VIEWED_NAME"]=="Y"):?>
				<div class="v-name"><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=TruncateText($arItem['NAME'], 35)?></a></div>
			<?endif?>
			<?if($arParams["VIEWED_PRICE"]=="Y" && $arItem["CAN_BUY"]=="Y"):?>
				<div class="v-price"><?=$arItem["PRICE_FORMATED"]?></div>
			<?endif?>
		</div>
	<?endforeach;?>
</div>
<?endif;?>