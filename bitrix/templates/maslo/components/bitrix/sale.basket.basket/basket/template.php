<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arUrls = Array(
	"delete" => $APPLICATION->GetCurPage()."?".$arParams["ACTION_VARIABLE"]."=delete&id=#ID#",
	"delay" => $APPLICATION->GetCurPage()."?".$arParams["ACTION_VARIABLE"]."=delay&id=#ID#",
	"add" => $APPLICATION->GetCurPage()."?".$arParams["ACTION_VARIABLE"]."=add&id=#ID#",
);

$arBasketJSParams = array(
	'SALE_DELETE' => GetMessage("SALE_DELETE"),
	'SALE_DELAY' => GetMessage("SALE_DELAY"),
	'SALE_TYPE' => GetMessage("SALE_TYPE"),
	'TEMPLATE_FOLDER' => $templateFolder,
	'DELETE_URL' => $arUrls["delete"],
	'DELAY_URL' => $arUrls["delay"],
	'ADD_URL' => $arUrls["add"]
);
?>
<script type="text/javascript">
	var basketJSParams = <?=CUtil::PhpToJSObject($arBasketJSParams);?>;
</script>
<?
$APPLICATION->AddHeadScript($templateFolder."/script.js");

if (strlen($arResult["ERROR_MESSAGE"]) <= 0)
{
	?>
	<div id="warning_message">
		<?
		if (is_array($arResult["WARNING_MESSAGE"]) && !empty($arResult["WARNING_MESSAGE"]))
		{
			foreach ($arResult["WARNING_MESSAGE"] as $v)
				echo ShowError($v);
		}
		?>
	</div>
	<?

	$normalCount = count($arResult["ITEMS"]["AnDelCanBuy"]);
	$normalHidden = ($normalCount == 0) ? "style=\"display:none\"" : "";

	$delayCount = count($arResult["ITEMS"]["DelDelCanBuy"]);
	$delayHidden = ($delayCount == 0) ? "style=\"display:none\"" : "";

	$subscribeCount = count($arResult["ITEMS"]["ProdSubscribe"]);
	$subscribeHidden = ($subscribeCount == 0) ? "style=\"display:none\"" : "";

	$naCount = count($arResult["ITEMS"]["nAnCanBuy"]);
	$naHidden = ($naCount == 0) ? "style=\"display:none\"" : "";

	?>
	 	<form class="shopping-cart sky-form" action="<?=POST_FORM_ACTION_URI?>" name="basket_form" id="basket_form">
			<div id="basket_form_container">
		 		<div class="header-tags">
			        <div class="overflow-h">
			            <h2>Корзина</h2>
			            <p>Проверьте ваш заказ</p>
			            <i class="rounded-x fa fa-check"></i>
		           </div>
	        	</div>
				<?  include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items.php"); ?>
				<div class="header-tags">
		          <div class="overflow-h">
		            <h2>Оформление</h2>
			        <p>Доставка и оплата</p>
		            <i class="rounded-x fa fa-home"></i> </div>
		        </div>
		        <section class="billing-info"></section>
				<input type="hidden" name="BasketOrder" value="BasketOrder" />
				<!-- <input type="hidden" name="ajax_post" id="ajax_post" value="Y"> -->
			</div>
		</form>
	<?
}
else
{
	ShowError($arResult["ERROR_MESSAGE"]);
}
?>