<?
if (file_exists($_SERVER["DOCUMENT_ROOT"]."/local/modules/pavelbabich.kassa/admin/" . basename(__FILE__))) {
	require_once($_SERVER["DOCUMENT_ROOT"]."/local/modules/pavelbabich.kassa/admin/" . basename(__FILE__));
} else {
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/pavelbabich.kassa/admin/" . basename(__FILE__));
}
?>