<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?if($params["ERROR"]):?>
	<?foreach($params["ERROR"] as $error):?>
		<p><font class="errortext"><?=$error?></font></p>
	<?endforeach?>
<?else:?>
	<form id="payRaiffeisenBank" action="<?=$params["URL_INIT"]?>" method="post">
		<input type="hidden" name="PurchaseAmt" value="<?=$params["PurchaseAmt"]?>" />
		<input type="hidden" name="PurchaseDesc" value="<?=$params["PurchaseDesc"]?>" />
		<input type="hidden" name="CountryCode" value="643" />
		<input type="hidden" name="CurrencyCode" value="643" />
		<input type="hidden" name="MerchantName" value="<?=$params["MerchantName"]?>" />
		<input type="hidden" name="MerchantID" value="<?=$params["MerchantID"]?>" />
		<input type="hidden" name="MerchantURL" value="<?=$params["MerchantURL"]?>" />
		<input type="hidden" name="MerchantCity" value="<?=$params["MerchantCity"]?>" />
		<input type="hidden" name="SuccessURL" value="<?=$params["SuccessURL"]?>" />
		<input type="hidden" name="FailURL" value="<?=$params["FailURL"]?>" />
		<input type="hidden" name="Language" value="<?=$params["Language"]?>" />
		<?if (strlen($params["HMAC"]) > 0):?>
			<input type="hidden" name="HMAC" value="<?=$params["HMAC"]?>" />
		<?endif?>
		<input type="hidden" name="Mobile" value="<?=$params["Mobile"]?>" />
		<?if ($params["ATOL"] == "Y") :?>
			<input type="hidden" name="Ext1" value='<?=$params['Ext1']?>'>
			<input type="hidden" name="Ext2" value='<?=$params['Ext2']?>'>
		<?endif?>
		<?if($params["NEW_WINDOW"] != "Y"):?>
			<input type="submit" value="Оплатить"  />
		<?endif?>	
	</form>
	<?if($params["NEW_WINDOW"] == "Y"):?>
		<script>
			document.getElementById("payRaiffeisenBank").submit();
		</script>
	<?endif?>
<?endif?>