<?
IncludeModuleLangFile(__FILE__, 'ru');
if(class_exists("pavelbabich_kassa")) return;

Class pavelbabich_kassa extends CModule
{
	var $MODULE_ID = "pavelbabich.kassa";
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;

	function pavelbabich_kassa()
	{
		$arModuleVersion = array();

		$path = str_replace("\\", "/", __DIR__);
		include($path . "/version.php");

		if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
			$this->MODULE_VERSION = $arModuleVersion["VERSION"];
			$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		}

		$this->MODULE_NAME = GetMessage("PKASSA_MAIN_MODULE_INSTALL_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("PKASSA_MAIN_MODULE_INSTALL_DESCR");
		$this->PARTNER_NAME = "Pavel Babich";
		$this->PARTNER_URI = "https://vk.com/skif_p";
	}

	function DoInstall()
	{
		RegisterModule($this->MODULE_ID);
		CAgent::RemoveModuleAgents($this->MODULE_ID);
		CAgent::AddAgent(
			"PKASSAModuleMain::SendPaidOrdersToEvotor();",
			$this->MODULE_ID,
			"N",
			60,
			date("d.m.Y H:i:s", time() + 60),
			"Y",
			date("d.m.Y H:i:s", time() + 60),
			9999
		);
        //RegisterModuleDependences("sale", "OnSalePayOrder", "pavelbabich.kassa", "PKASSAModuleMain", "PayOrderEvent");
		CopyDirFiles(__DIR__ ."/files/admin/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin/", true);
        
        if(CModule::IncludeModule("sale")){
            $arPaydOrders = array();
            $rsOrders = CSaleOrder::GetList(array(), Array("PAYED" => "Y"),false,false,array("ID"));
            while ($arOrders = $rsOrders->GetNext())$arPaydOrders[]=$arOrders["ID"];
            file_put_contents($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/pavelbabich.kassa/expaidorders.php",implode(";",$arPaydOrders));
        }
		return true;
	}

	function DoUninstall()
	{
		CAgent::RemoveModuleAgents($this->MODULE_ID);
        //UnRegisterModuleDependences("sale", "OnSalePayOrder", "pavelbabich.kassa", "PKASSAModuleMain", "PayOrderEvent");
		UnRegisterModule($this->MODULE_ID);
		DeleteDirFilesEx("/bitrix/admin/pavelbabich_kassa.php");
		return true;
	}
}

?>