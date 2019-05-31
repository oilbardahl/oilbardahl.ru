<?
$_SERVER["DOCUMENT_ROOT"] = "...";
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if(CModule::IncludeModule("pavelbabich.kassa")){
    PKASSAModuleMain::SendPaidOrdersToEvotor();
    //file_put_contents($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/pavelbabich.kassa/cron.php", "start:".date("d-m-Y H:i:s")."\n",FILE_APPEND);
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>