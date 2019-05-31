<?php
IncludeModuleLangFile(__FILE__);

class czebra_raiffeisenbank extends CModule
{
    var $MODULE_ID = "czebra.raiffeisenbank";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;

	public function czebra_raiffeisenbank()
	{
        $arModuleVersion = array();

        $path = str_replace("\\", "/", __FILE__);
        $path = substr($path, 0, strlen($path) - strlen("/index.php"));
        include($path."/version.php");
        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }
		$this->MODULE_NAME = GetMessage('CZ_RB_MODULE_NAME');
		$this->MODULE_DESCRIPTION = GetMessage('CZ_RB_MODULE_DESCRIPTION');
		$this->PARTNER_NAME = GetMessage('CZ_NAME_COMPANY');
		$this->MODULE_GROUP_RIGHTS = 'N';
		$this->PARTNER_URI = 'http://www.czebra.ru/';
    }

	public function DoInstall()
	{	
		$isConverted = \Bitrix\Main\Config\Option::get('main', '~sale_converted_15', "N");	
		if (!CModule::IncludeModule('sale') || !(defined("SM_VERSION")  && version_compare(SM_VERSION, "16.0.0") >= 0  && $isConverted == "Y")) {
			return false;
		}
		
		CopyDirFiles($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/'.$this->MODULE_ID.'/install/install/payment',
			$_SERVER['DOCUMENT_ROOT'] . '/bitrix/php_interface/include/sale_payment', true, true);
		if (SITE_CHARSET == "UTF-8") {
			CopyDirFiles($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/'.$this->MODULE_ID.'/install/install/result-page-utf8',
				$_SERVER['DOCUMENT_ROOT'] . '/bitrix', true, true);
		} else {
			CopyDirFiles($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/'.$this->MODULE_ID.'/install/install/result-page-win1251',
				$_SERVER['DOCUMENT_ROOT'] . '/bitrix', true, true);
		}
		CopyDirFiles($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/'.$this->MODULE_ID.'/install/install/raiffeisenbank.png',
			$_SERVER['DOCUMENT_ROOT'] . '/bitrix/images/sale/sale_payments', true, true);
			
		RegisterModule($this->MODULE_ID);				
		return true;
    }
	
	public function DoUninstall()
	{		
		DeleteDirFilesEx("/bitrix/php_interface/include/sale_payment/raiffeisenbank");
		DeleteDirFilesEx("/bitrix/raiffeisenbank");

		UnRegisterModule($this->MODULE_ID);
		return true;
    }
}
