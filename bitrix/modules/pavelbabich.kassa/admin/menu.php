<?
CModule::IncludeModule("pavelbabich.kassa");
if(PKASSAMainSettings::GetSiteSetting("HANDSELF")=="Y"){
    CJSCore::Init('jquery');
    $APPLICATION->AddHeadScript('/bitrix/modules/pavelbabich.kassa/admin/add_buttom.js');
}
if ($USER->IsAdmin()) {
	$aMenu = array(
		"parent_menu" => "global_menu_settings",
		"icon" => "nls_menu_icon",
		"page_icon" => "default_page_icon",
		"sort"=>"900",
		"text"=>GetMessage("PKASSA_MAIN_MODULE_PAGE_PKASSA_SETTINGS"),
		"title"=>GetMessage("PKASSA_MAIN_MODULE_PAGE_PKASSA_SETTINGS"),
		"url"=>"/bitrix/admin/pavelbabich_kassa.php",
		"more_url"=>array(),
	);
	return $aMenu;
}
return false;
?>