<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;
CModule::IncludeModule("sale");

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $item
 * @var array $actualItem
 * @var array $minOffer
 * @var array $itemIds
 * @var array $price
 * @var array $measureRatio
 * @var bool $haveOffers
 * @var bool $showSubscribe
 * @var array $morePhoto
 * @var bool $showSlider
 * @var string $imgTitle
 * @var string $productTitle
 * @var string $buttonSizeClass
 * @var CatalogSectionComponent $component
 */
$res = CIBlockElement::GetByID($item['ID']);
if($obRes = $res->GetNextElement()) {
		$item_for_dealer = $obRes->GetProperty("DEALER_ITEM");
		$item_for_dealer = $item_for_dealer["VALUE_XML_ID"];

		$item_article = $obRes->GetProperty("ARTICLE");
		$item_article = $item_article['VALUE'];

		$item_vyazcost = $obRes->GetProperty("VYAZCOST");
		$item_vyazcost = $item_vyazcost['VALUE'];

		$item_dopusky = $obRes->GetProperty("DOPUSKY_DILER");
		$item_dopusky = $item_dopusky['VALUE'];

		$item_kol_v_upakovke = $obRes->GetProperty("COL_V_UPAKOVKE_DILER");
		$item_kol_v_upakovke = $item_kol_v_upakovke['VALUE'];

		$item_litraj = $obRes->GetProperty("LITRAJ_DILER");
		$item_litraj = $item_litraj['VALUE'];	

		$item_price_upakovka = $obRes->GetProperty("DEALER_PRICE_UPAKOVKA");
		$item_price_upakovka = $item_price_upakovka['VALUE'];

		// цена Дилер (от 500 т.р.)
		$item_price_from_500 = $obRes->GetProperty("DEALER_PRICE_FROM_500");
		$item_price_from_500 = $item_price_from_500['VALUE'];

		// цена VIP (от 100 т.р.)
		$item_price_from_vip_100 = $obRes->GetProperty("VIP_FROM_100");
		$item_price_from_vip_100 = $item_price_from_vip_100['VALUE'];

		// цена Кр.Опт
		$item_price_from_cu_wholesale = $obRes->GetProperty("CU_WHOLESALE");
		$item_price_from_cu_wholesale = $item_price_from_cu_wholesale['VALUE'];
		
	}
	$collBasketItems = CSaleBasket::GetList(
		array(
				"NAME" => "ASC",
				"ID" => "ASC",
				"PRODUCT_ID" => "ASC",
			),
		array(
				"FUSER_ID" => CSaleBasket::GetBasketUserID(),
				"LID" => SITE_ID,
				"ORDER_ID" => "NULL",
				"PRODUCT_ID" => $item['ID'],
			),
		false,
		false,
		array("ID", "PRODUCT_ID", "QUANTITY", "PRICE", "NOTES")
	);
	
	$quantity_dealer_item = ceil($collBasketItems->arResult['0']['QUANTITY']);
?>
<tr class="dealer_tovar_item <?php if($quantity_dealer_item > 0){echo 'dealer_item_active';}?>">
	<th width="70">
		<?=$item_article?>
	</th>
	<th width="112">
		<span class="about_item open_modal">
			<?=$productTitle?>
		</span>
	</th>
	<th width="112">
		<?=$item_dopusky?>
	</th>
	<th width="76">
		<?=$item_vyazcost?>
	</th>
	<th width="78">
		<?=$item_kol_v_upakovke?>
	</th>
	<th width="66">
		<?=$item_litraj?>
	</th>
	<th width="92">
		<?$arItem = CPrice::GetBasePrice($item['ID'])?>
		<? if (in_array(20, $USER->GetUserGroupArray()) == "1"){
			echo CurrencyFormat($item_price_from_500,$arItem['CURRENCY']); // цена Дилер (от 500 т.р.)
		} elseif (in_array(21, $USER->GetUserGroupArray()) == "1") {
			echo CurrencyFormat($item_price_from_vip_100,$arItem['CURRENCY']); // цена VIP (от 100 т.р.)
		} elseif (in_array(22, $USER->GetUserGroupArray()) == "1") {
			echo CurrencyFormat($item_price_from_cu_wholesale,$arItem['CURRENCY']); // цена Кр.Опт
		} ?>
	</th>
	<th width="104">
		<div class="add_to_cart_div">
			<p class="dealer_item_name" style="display: none;"><?=$productTitle?></p>
			<p class="dealer_id" style="display: none;"><?=$arItem['PRODUCT_ID']?></p>
			<p class="dealer_price_to_backet" style="display: none;">
				<? if (in_array(20, $USER->GetUserGroupArray()) == "1"){
					echo $item_price_from_500; // цена Дилер (от 500 т.р.)
				} elseif (in_array(21, $USER->GetUserGroupArray()) == "1") {
					echo $item_price_from_vip_100; // цена VIP (от 100 т.р.)
				} elseif (in_array(22, $USER->GetUserGroupArray()) == "1") {
					echo $item_price_from_cu_wholesale; // цена Кр.Опт
				} ?>
			</p>
      		<button type="button" class="item_dealer_minus" name="subtract" onclick="" value="-">-</button>
      		<input type="text" class="dealer_item_coll" name='QUANTITY_<?=$arItem['PRODUCT_ID']?>' data-id="<?=$arItem['PRODUCT_ID']?>" value="<?=($quantity_dealer_item ? $quantity_dealer_item : '0')?>" id='QUANTITY_<?=$arItem['PRODUCT_ID']?>' onchange="">
      		<button type="button" class="item_dealer_plus" name="add" onclick="" value="+">+</button>
        </div>
	</th>
	<th width="151" class="final_price_dealer">
		<span class="dealer_fimal_prise_item_span_1">
			<? if (in_array(20, $USER->GetUserGroupArray()) == "1"){
				echo CurrencyFormat($item_price_from_500*$quantity_dealer_item,$arItem['CURRENCY']);
			} elseif (in_array(21, $USER->GetUserGroupArray()) == "1") {
				echo CurrencyFormat($item_price_from_vip_100*$quantity_dealer_item,$arItem['CURRENCY']);
			} elseif (in_array(22, $USER->GetUserGroupArray()) == "1") {
				echo CurrencyFormat($item_price_from_cu_wholesale*$quantity_dealer_item,$arItem['CURRENCY']);
			} ?>	
		</span>
		<span class="dealer_fimal_prise_item_span_2"><i class="fa fa-times" aria-hidden="true"></i></span>
	</th>
</tr>