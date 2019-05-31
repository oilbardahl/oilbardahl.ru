<?php
use \Bitrix\Main\Application;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Web\HttpClient;

define("NO_KEEP_STATISTIC", true); 
define("NOT_CHECK_PERMISSIONS", true);

define("STOP_STATISTICS", true);
define('NO_AGENT_CHECK', true);
define("DisableEventsCheck", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if (Loader::IncludeModule("sale") && Loader::IncludeModule("czebra.raiffeisenbank")) {
    $request = Application::getInstance()->getContext()->getRequest();
    $server = Application::getInstance()->getContext()->getServer();

    $arParam = array();
    foreach ($request as $key => $value) {
        if ($key == "descr") {
            $arValue = explode("_", $value);
            $arParam["CZ_HANDLER_ID"] = $arValue[0];
            $arParam["descr"] = substr_replace($value, '', 0, strlen($arValue[0]."_"));
        } else {
            $arParam[$key] = $value;
        }

    }
    $arParam["CZ_HANDLER_NAME"] = "RAIFFEISENBANKHANDLER";

    $httpClient = new HttpClient(); 
    $httpClient->setHeader('Content-Type', 'application/x-www-form-urlencoded', true);
    $url = $request->isHttps() ? "https://" . $server->getHttpHost() : "http://" . $server->getHttpHost();
    $url .= "/bitrix/tools/sale_ps_result.php?" . http_build_query($arParam);
    $response = $httpClient->get($url);
    echo $response;
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
