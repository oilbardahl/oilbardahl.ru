<?require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");?>
<?
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH']==='XMLHttpRequest'
	&& CModule::IncludeModule("iblock") && CModule::IncludeModule("sale") && CModule::IncludeModule("catalog") && isset($_REQUEST["id"]) && $_REQUEST["action-main"]=="oneproduct" && isset($_REQUEST["quantity"]))
{
   $APPLICATION->RestartBuffer();
   $arProductInCart = array();
   $dbBasketItems = CSaleBasket::GetList(
        array(
                "NAME" => "ASC",
                "ID" => "ASC"
            ),
        array(
                "FUSER_ID" => CSaleBasket::GetBasketUserID(),
                "ORDER_ID" => "NULL"
            ),
        false,
        false,
        array("ID", "CALLBACK_FUNC", "MODULE",
              "PRODUCT_ID", "QUANTITY", "DELAY",
              "CAN_BUY", "PRICE", "WEIGHT")
    );
	while ($arItems = $dbBasketItems->Fetch()){
		$arProductInCart[$arItems['PRODUCT_ID']] = $arItems['QUANTITY'];
	}
   $arProductList = explode(",", $_REQUEST["id"]);

   $arProductQuantity = explode(",", $_REQUEST["quantity"]);
   //AddMessage2Log($arProductQuantity);
   //AddMessage2Log($arProductList);

   foreach($arProductList as $key=>$arItem):
   	 // CSaleBasket::Delete($arItem);
   	  $quantityElement = (int)$arProductQuantity[$key];
   	  Add2BasketByProductID(
		  (int)$arItem,
		  $quantityElement,
		  array(),
		  array()
	  );
   endforeach;
}
?>