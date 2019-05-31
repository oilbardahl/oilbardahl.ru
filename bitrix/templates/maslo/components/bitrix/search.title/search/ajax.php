<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(!empty($arResult["CATEGORIES"])):?>
	<table class="title-search-result">
		<?foreach($arResult["CATEGORIES"] as $category_id => $arCategory):?>
			<?foreach($arCategory["ITEMS"] as $i => $arItem):?>
			<tr>
				<?if($category_id === "all"):?>
					<td class="title-search-all"><a href="<?echo $arItem["URL"]?>"><?echo $arItem["NAME"]?></td>
				<?elseif(isset($arItem["ICON"])):?>
					<td class="title-search-item"><a href="<?echo $arItem["URL"]?>"><img src="<?echo $arItem["ICON"]?>"><?echo $arItem["NAME"]?></td>
				<?else:?>
					<td class="title-search-more"><a href="<?echo $arItem["URL"]?>"><?echo $arItem["NAME"]?></td>
				<?endif;?>
			</tr>
			<?endforeach;?>
		<?endforeach;?>
	</table><div class="title-search-fader"></div>
<?endif;
?>