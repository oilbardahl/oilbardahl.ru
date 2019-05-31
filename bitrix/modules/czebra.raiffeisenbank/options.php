<?php
$moduleID = "czebra.raiffeisenbank";
$landPref = "CZ_RB_";

CModule::IncludeModule($moduleID);

if (!$USER->CanDoOperation('czebra.raiffeisenbank_settings')) {
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/options.php");
IncludeModuleLangFile(__FILE__);

$aTabs = array(array(
	"DIV" => "edit1", 
	"TAB" => GetMessage($landPref.'TAB_NAME'), 
	"ICON" => "", 
	"TITLE" => GetMessage($landPref.'TAB_NAME_TITLE')
));

$tabControl = new CAdminTabControl("tabControl", $aTabs);

$tabControl->Begin();
$tabControl->BeginNextTab();

$key_test = COption::GetOptionString($moduleID, "SECRET_KEY_TEST", "-");
$key_test = ($key_test == "-") ? $key_test : substr($key_test,0, 5)."...".substr($key_test,-5, 5);

$key = COption::GetOptionString($moduleID, "SECRET_KEY", "-");
$key = ($key == "-") ? $key : substr($key,0, 5)."...".substr($key,-5, 5);
?>
<h2><?=GetMessage($landPref.'CAPTION')?></h2>
<h3><?=GetMessage($landPref.'DESC_TEST_KEY')?><?=$key_test?></h3>
<h3><?=GetMessage($landPref.'DESC_KEY')?><?=$key?></h3>
<form action="/bitrix/raiffeisenbank/key.php" method="POST">
	<p><?=GetMessage($landPref.'NAME_LABEL')?></p>
   	<input name="name" type="text" value="" />
   	<p><?=GetMessage($landPref.'PWR_LABEL')?></p>
	<input name="psw" type="password" value="" />
   	<p><?=GetMessage($landPref.'MERCHNAT_LABEL')?></p>
	<input name="MerchantID" type="text" value="" />
	<br/><br/>
	<input name="test" type="checkbox" value="Y" />
	<label for="test"><?=GetMessage($landPref.'TEST')?></label>
   	<br/><br/>
	<?if($request["errors"] == "problem"):?>
		<p style="color: #ee0000;"><?=GetMessage($landPref.'ERROR')?></p>
	<?endif?>
	<input type="submit" value="<?=GetMessage($landPref.'GET_RESULT')?>" />
</form>
<p><?=GetMessage($landPref.'DESCRIPTION')?></p>

<?
$tabControl->BeginNextTab();
$tabControl->End();
?>

