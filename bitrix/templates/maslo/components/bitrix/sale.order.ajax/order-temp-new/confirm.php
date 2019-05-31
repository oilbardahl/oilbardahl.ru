<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if (!empty($arResult["ORDER"]))
{	

	// обновление номера телефона в карточки клиента  после оформление заказа
	$user_id_for_tel = $USER->GetID();


	$arFilter = Array(
	   "USER_ID" => $user_id_for_tel,
	   ">=DATE_INSERT" => date($DB->DateFormatToPHP(CSite::GetDateFormat("SHORT")), mktime(0, 0, 0, date("n"), 1, date("Y")))
	   );

	$db_sales = CSaleOrder::GetList(array("DATE_INSERT" => "ASC"), $arFilter);
	$data_sort_for_id = array();
	while ($ar_sales = $db_sales->Fetch())
	{
	   array_push($data_sort_for_id, $ar_sales['ID']);
	   
	}
	$id_sort_for_data = array_pop($data_sort_for_id);


	if($id_sort_for_data):
	  $arFilter1 = Array (
	        "ORDER_ID" => $id_sort_for_data
	    );
	    $db_sales_ord1 = CSaleOrderPropsValue::GetList(array(), $arFilter1);
	    $id_sort_for_tel = array();
	    while ($ar_salesord1 = $db_sales_ord1->Fetch())
	    {
	        array_push($id_sort_for_tel, $ar_salesord1);
	    }
	  $new_plus = "+";
	  $new_tel = $new_plus.$id_sort_for_tel["2"]["VALUE"];   
	endif;
	$new_user_tel = new CUser;
	$update_tel = Array(
	    "PERSONAL_PHONE"    => $new_tel,
	);

	$new_user_tel->Update($user_id_for_tel, $update_tel);
	// конец обновление номера телефона в карточки клиента  после оформление заказа

	// обновление адреса у диллеров после заказа
	$arGroupsDealer = CUser::GetUserGroup($USER->GetID());
	if (in_array("19", $arGroupsDealer)) {
		$kod_goroda = $id_sort_for_tel['5']['VALUE'];
	    $arLocs = CSaleLocation::GetByID($kod_goroda, LANGUAGE_ID);
	    $new_dealer_addres = ($id_sort_for_tel['6']['VALUE']." ".$arLocs['COUNTRY_NAME']." ".$arLocs['CITY_NAME']." ".$id_sort_for_tel['3']['VALUE']." ".$id_sort_for_tel['4']['VALUE']." ".$id_sort_for_tel['8']['VALUE']." ".$id_sort_for_tel['9']['VALUE']." ".$id_sort_for_tel['10']['VALUE']);
	    $new_dealer_address = new CUser;
	    $update_dealer_address = Array(
		    "UF_ADRES_DIL"     => $new_dealer_addres,
		);
		$new_dealer_address->Update($user_id_for_tel, $update_dealer_address);
	}

	// смена статуса заказа у диллеров
	if ((in_array(1, $USER->GetUserGroupArray()) != "1") && (in_array(19, $USER->GetUserGroupArray()) == "1")){
		if (!CSaleOrder::StatusOrder($arResult["ORDER"]["ID"], "DP")){
				
		}
	}
	?>
	<div class="olololo" style="display: none;"><? print_r($arResult);?></div>
	<b><?=GetMessage("SOA_TEMPL_ORDER_COMPLETE")?></b><br /><br />
	<table class="sale_order_full_table">
		<tr>
			<td>
				<?= GetMessage("SOA_TEMPL_ORDER_SUC", Array("#ORDER_DATE#" => $arResult["ORDER"]["DATE_INSERT"], "#ORDER_ID#" => $arResult["ORDER"]["ACCOUNT_NUMBER"]))?>
				<br /><br />
				<?= GetMessage("SOA_TEMPL_ORDER_SUC1", Array("#LINK#" => $arParams["PATH_TO_PERSONAL"])) ?>
			</td>
		</tr>
	</table>
	<script src="<?=SITE_TEMPLATE_PATH?>/assets/plugins/jquery/jquery.min.js"></script>
	<script type="text/javascript">
       $(document).ready(function () {
           if ($(".bx_order_make form input[type='submit']").length>0){
              $(".bx_order_make form").submit();
           }
       });
    </script>
	<?
	if (!empty($arResult["PAY_SYSTEM"]))
	{
		?>
		<br /><br />

		<table class="sale_order_full_table">
			<tr>
				<td class="ps_logo">

					<div class="pay_name"><?=GetMessage("SOA_TEMPL_PAY")?></div>
					<?=CFile::ShowImage($arResult["PAY_SYSTEM"]["LOGOTIP"], 100, 100, "border=0", "", false);?>
					<div class="paysystem_name"><?= $arResult["PAY_SYSTEM"]["NAME"] ?></div><br>
				</td>
				<?php if ($arResult["PAY_SYSTEM"]["ID"] == "13") { ?>
					<td>
						<!--<div style="display: none;" id="print_bill_on_list"><?php //print_r($arResult["PAY_SYSTEM"]["BUFFERED_OUTPUT"]);?></div>-->	
						<!--<input class="printing_invoices" type="button" value="Печать счета" onclick="PrintElem('#print_bill_on_list')" />-->
						<!--<input class="download_account" type="button" value="Скачать счет" onclick="print()" />-->
					</td>
				<?php } ?>
			</tr>
			<?
			if (strlen($arResult["PAY_SYSTEM"]["ACTION_FILE"]) > 0 && $arResult["PAY_SYSTEM"]["ID"] != "13" && $arResult["PAY_SYSTEM"]["ID"] != "15")
			{
				?>
				<tr>
					<td>
						<?
						if ($arResult["PAY_SYSTEM"]["NEW_WINDOW"] == "Y")
						{
							?>
							<script language="JavaScript">
								window.open('<?=$arParams["PATH_TO_PAYMENT"]?>?ORDER_ID=<?=urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]))?>&PAYMENT_ID=<?=$arResult['ORDER']["PAYMENT_ID"]?>');
							</script>
							<?= GetMessage("SOA_TEMPL_PAY_LINK", Array("#LINK#" => $arParams["PATH_TO_PAYMENT"]."?ORDER_ID=".urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]))."&PAYMENT_ID=".$arResult['ORDER']["PAYMENT_ID"]))?>
							<?
							if (CSalePdf::isPdfAvailable() && CSalePaySystemsHelper::isPSActionAffordPdf($arResult['PAY_SYSTEM']['ACTION_FILE']))
							{
								?><br />
								<?= GetMessage("SOA_TEMPL_PAY_PDF", Array("#LINK#" => $arParams["PATH_TO_PAYMENT"]."?ORDER_ID=".urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]))."&pdf=1&DOWNLOAD=Y")) ?>
								<?
							}
						}
						else
						{
							if (strlen($arResult["PAY_SYSTEM"]["PATH_TO_ACTION"])>0)
							{
								try
								{
									include($arResult["PAY_SYSTEM"]["PATH_TO_ACTION"]);
								}
								catch(\Bitrix\Main\SystemException $e)
								{
									if($e->getCode() == CSalePaySystemAction::GET_PARAM_VALUE)
										$message = GetMessage("SOA_TEMPL_ORDER_PS_ERROR");
									else
										$message = $e->getMessage();

									echo '<span style="color:red;">'.$message.'</span>';
								}
							}
						}
						?>
					</td>
				</tr>
				<?
			}
			?>
		</table>
		<?
	}
}
else
{
	?>
	<b><?=GetMessage("SOA_TEMPL_ERROR_ORDER")?></b><br /><br />

	<table class="sale_order_full_table">
		<tr>
			<td>
				<?=GetMessage("SOA_TEMPL_ERROR_ORDER_LOST", Array("#ORDER_ID#" => $arResult["ACCOUNT_NUMBER"]))?>
				<?=GetMessage("SOA_TEMPL_ERROR_ORDER_LOST1")?>
			</td>
		</tr>
	</table>
	<?
}
?>
