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
		$item_article = $obRes->GetProperty("CML2_ARTICLE");
		$item_article = $item_article['VALUE'];

		$item_vyazcost = $obRes->GetProperty("VYAZKOST");
		$item_vyazcost = $item_vyazcost['VALUE'];

		$item_dopusky = $obRes->GetProperty("DOPUSKI_DILER");
		//$item_dopusky = $item_dopusky['VALUE'];

		$item_kol_v_upakovke = $obRes->GetProperty("KOL_VO_V_UPAKOVKE");
		$item_kol_v_upakovke = $item_kol_v_upakovke['VALUE'];

		$item_litraj = $obRes->GetProperty("LITRAZH_DILER");
		$item_litraj = $item_litraj['VALUE'];	

		// цена Дилер (от 500 т.р.)
		$item_price_from_500 = $obRes->GetProperty("TSENA_DILER");
		$item_price_from_500 = $item_price_from_500['VALUE'];

		// цена VIP (от 100 т.р.)
		$item_price_from_vip_100 = $obRes->GetProperty("TSENA_VIP");
		$item_price_from_vip_100 = $item_price_from_vip_100['VALUE'];

		// цена Кр.Опт
		$item_price_from_cu_wholesale = $obRes->GetProperty("TSENA_KR_OPT");
		$item_price_from_cu_wholesale = $item_price_from_cu_wholesale['VALUE'];
		//$item_price_from_cu_wholesale = str_replace('/\s/','',$item_price_from_cu_wholesale);
		
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
	<th width="76">
		<?=$item_vyazcost?>
	</th>
	<th width="78">
		<?=$item_kol_v_upakovke?>
	</th>
	<th width="66">
		<?=$item_litraj?>
	</th>
	<th width="112">
		<?
		if (!CModule::IncludeModule('highloadblock'))
		continue;
		//сначала выбрать информацию о ней из базы данных
		$hldata = Bitrix\Highloadblock\HighloadBlockTable::getById(3)->fetch();
		//затем инициализировать класс сущности
		$hlentity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
		$hlDataClass = $hldata['NAME'].'Table';
		foreach($item_dopusky['~VALUE'] as $item_dopusky_code){
			$result = $hlDataClass::getList(array(
				'select' => array('UF_NAME'),
				'order' => array('UF_NAME' =>'ASC'),
				'filter' => array('UF_XML_ID'=>$item_dopusky_code)
			));
			while($res = $result->fetch())
			{
				echo $res['UF_NAME'].' ';
			}
		}
		?>
	</th>
	<th width="92">
		<?$arItem = CPrice::GetBasePrice($item['ID'])?>
		<? if (in_array(20, $USER->GetUserGroupArray()) == "1"){
			if ($item_price_from_500) {
				echo '<span class="item_prise_for_1">'.$item_price_from_500.'</span>'; // цена Дилер (от 500 т.р.)
				echo str_replace(",", " ", number_format($item_price_from_500));
				echo " руб.";
			}
		} elseif (in_array(21, $USER->GetUserGroupArray()) == "1") {
			if ($item_price_from_vip_100) {
				echo '<span class="item_prise_for_1">'.$item_price_from_vip_100.'</span>';  // цена VIP (от 100 т.р.)
				echo str_replace(",", " ", number_format($item_price_from_vip_100));
				echo " руб.";
			}
		} elseif (in_array(22, $USER->GetUserGroupArray()) == "1") {
			if ($item_price_from_cu_wholesale) {
				echo '<span class="item_prise_for_1">'.$item_price_from_cu_wholesale.'</span>';  // цена Кр.Опт
				echo str_replace(",", " ", number_format($item_price_from_cu_wholesale));
				echo " руб.";
			}
		} ?>
	</th>
	<th width="104">
		<p class="dealer_id" style="display: none;"><?=$item['ID']?></p>
		<? if (in_array(20, $USER->GetUserGroupArray()) == "1"){
			if ($item_price_from_500) {
				?>
					<div class="add_to_cart_div">
						<p style="display: none" class="dealer_xml_id"><?=$item["XML_ID"]?></p>
						<p style="display: none" class="dealer_catalog_xml_id"><?=$item["IBLOCK_EXTERNAL_ID"]?></p>
						<p class="item_name" style="display: none"><?=$productTitle?></p>
						<p class="dealer_price_to_backet" style="display: none;">
							<? if (in_array(20, $USER->GetUserGroupArray()) == "1"){
								echo $item_price_from_500; // цена Дилер (от 500 т.р.)
							} elseif (in_array(21, $USER->GetUserGroupArray()) == "1") {
								echo $item_price_from_vip_100; // цена VIP (от 100 т.р.)
							} elseif (in_array(22, $USER->GetUserGroupArray()) == "1") {
								echo $item_price_from_cu_wholesale; // цена Кр.Опт
							} ?>
						</p>
						<span class="dealer_item_coll_to_span" style="display: none;"></span>
			      		<button type="button" class="item_dealer_minus" name="subtract" onclick="" value="-">-</button>
			      		<input type="text" class="dealer_item_coll" name='QUANTITY_<?=$arItem['PRODUCT_ID']?>' data-id="<?=$arItem['PRODUCT_ID']?>" value="<?=($quantity_dealer_item ? $quantity_dealer_item : '0')?>" id='QUANTITY_<?=$arItem['PRODUCT_ID']?>' onchange="">
			      		<button type="button" class="item_dealer_plus" name="add" onclick="" value="+">+</button>
			        </div>
	      		<?
			} else {
				echo "";
			}
		} elseif (in_array(21, $USER->GetUserGroupArray()) == "1") {
			if ($item_price_from_vip_100) {
				?>
					<div class="add_to_cart_div">
						<p style="display: none" class="dealer_xml_id"><?=$item["XML_ID"]?></p>
						<p style="display: none" class="dealer_catalog_xml_id"><?=$item["IBLOCK_EXTERNAL_ID"]?></p>
						<p class="item_name" style="display: none"><?=$productTitle?></p>
						<p class="dealer_price_to_backet" style="display: none;">
							<? if (in_array(20, $USER->GetUserGroupArray()) == "1"){
								echo $item_price_from_500; // цена Дилер (от 500 т.р.)
							} elseif (in_array(21, $USER->GetUserGroupArray()) == "1") {
								echo $item_price_from_vip_100; // цена VIP (от 100 т.р.)
							} elseif (in_array(22, $USER->GetUserGroupArray()) == "1") {
								echo $item_price_from_cu_wholesale; // цена Кр.Опт
							} ?>
						</p>
						<span class="dealer_item_coll_to_span" style="display: none;"></span>
			      		<button type="button" class="item_dealer_minus" name="subtract" onclick="" value="-">-</button>
			      		<input type="text" class="dealer_item_coll" name='QUANTITY_<?=$arItem['PRODUCT_ID']?>' data-id="<?=$arItem['PRODUCT_ID']?>" value="<?=($quantity_dealer_item ? $quantity_dealer_item : '0')?>" id='QUANTITY_<?=$arItem['PRODUCT_ID']?>' onchange="">
			      		<button type="button" class="item_dealer_plus" name="add" onclick="" value="+">+</button>
			        </div>
	      		<?
			} else {
				echo "";
			}
		} elseif (in_array(22, $USER->GetUserGroupArray()) == "1") {
			if ($item_price_from_cu_wholesale) {
				?>
					<div class="add_to_cart_div">
						<p style="display: none" class="dealer_xml_id"><?=$item["XML_ID"]?></p>
						<p style="display: none" class="dealer_catalog_xml_id"><?=$item["IBLOCK_EXTERNAL_ID"]?></p>
						<p class="item_name" style="display: none"><?=$productTitle?></p>
						<p class="dealer_price_to_backet" style="display: none;">
							<? if (in_array(20, $USER->GetUserGroupArray()) == "1"){
								echo $item_price_from_500; // цена Дилер (от 500 т.р.)
							} elseif (in_array(21, $USER->GetUserGroupArray()) == "1") {
								echo $item_price_from_vip_100; // цена VIP (от 100 т.р.)
							} elseif (in_array(22, $USER->GetUserGroupArray()) == "1") {
								echo $item_price_from_cu_wholesale; // цена Кр.Опт
							} ?>
						</p>
						<span class="dealer_item_coll_to_span" style="display: none;"></span>
			      		<button type="button" class="item_dealer_minus" name="subtract" onclick="" value="-">-</button>
			      		<input type="text" class="dealer_item_coll" name='QUANTITY_<?=$arItem['PRODUCT_ID']?>' data-id="<?=$arItem['PRODUCT_ID']?>" value="<?=($quantity_dealer_item ? $quantity_dealer_item : '0')?>" id='QUANTITY_<?=$arItem['PRODUCT_ID']?>' onchange="">
			      		<button type="button" class="item_dealer_plus" name="add" onclick="" value="+">+</button>
			        </div>
	      		<?
			} else {
				echo "";
			}
		} ?>

	</th>
	<th width="151" class="final_price_dealer">
		<span class="dealer_fimal_prise_item_span_1">
			<? if (in_array(20, $USER->GetUserGroupArray()) == "1" && ($item_price_from_500)){
				echo str_replace(",", " ", number_format($item_price_from_500*$quantity_dealer_item));
				echo " руб.";
			} elseif (in_array(21, $USER->GetUserGroupArray()) == "1" && ($item_price_from_vip_100)){
				echo str_replace(",", " ", number_format($item_price_from_vip_100*$quantity_dealer_item));
				echo " руб.";
			} elseif (in_array(22, $USER->GetUserGroupArray()) == "1" && ($item_price_from_cu_wholesale)) {
				echo str_replace(",", " ", number_format($item_price_from_cu_wholesale*$quantity_dealer_item));
				echo " руб.";
			} ?>
		</span>
		<? if (in_array(20, $USER->GetUserGroupArray()) == "1" && ($item_price_from_500)){
			?>
			<span class="dealer_fimal_prise_item_span_2"><i class="fa fa-times" aria-hidden="true"></i></span>
			<?
		} elseif (in_array(21, $USER->GetUserGroupArray()) == "1" && ($item_price_from_vip_100)){
			?>
			<span class="dealer_fimal_prise_item_span_2"><i class="fa fa-times" aria-hidden="true"></i></span>
			<?
		} elseif (in_array(22, $USER->GetUserGroupArray()) == "1" && ($item_price_from_cu_wholesale)) {
			?>
			<span class="dealer_fimal_prise_item_span_2"><i class="fa fa-times" aria-hidden="true"></i></span>
			<?
		} ?>
	</th>
</tr>