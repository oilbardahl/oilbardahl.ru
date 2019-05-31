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
    $arParam = array(
        "xICBSXPProxy.ReqType" => "100",
        "xICBSXPProxy.Version" => "05.00",
        "xICBSXPProxy.UserName" => $request["name"],
        "xICBSXPProxy.UserPassword" => $request["psw"],
        "MerchantID" => $request["MerchantID"],
    );
    
    if ($request["test"] == 'Y') {
        $url = "https://e-commerce.raiffeisen.ru/portal_test/mrchtrnvw/trn_xml.jsp";
    } else {
        $url = "https://e-commerce.raiffeisen.ru/portal/mrchtrnvw/trn_xml.jsp";
    }

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_USERAGENT, "Opera/10.00 (Windows NT 5.1; U; ru) Presto/2.2.0");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/x-www-form-urlencoded'));
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($arParam));
    $response = curl_exec($curl);
    curl_close($curl);		
        

        $xml = simplexml_load_string($response);
        print_r(count($xml->Message->Parameter));
        if (count($xml->Message->Parameter) > 0) {
            foreach ($xml->Message->Parameter as $val) {
                if ($val["name"] == "Key") {
                    if ($request["test"] == 'Y') {
                        COption::SetOptionString('czebra.raiffeisenbank', 'SECRET_KEY_TEST', (string)$val->Value);
                    } else {
                        COption::SetOptionString('czebra.raiffeisenbank', 'SECRET_KEY', (string)$val->Value);
                    }
                }
            }
            LocalRedirect("/bitrix/admin/settings.php?mid=czebra.raiffeisenbank");
        }
}
LocalRedirect("/bitrix/admin/settings.php?mid=czebra.raiffeisenbank&errors=problem");    

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
