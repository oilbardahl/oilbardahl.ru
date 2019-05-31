<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include.php';
global $client;

CModule::IncludeModule("iblock");

if (!CModule::IncludeModule("intaro.retailcrm"))
    die();

$URL_RETAILCRM = COption::GetOptionString("intaro.retailcrm", "api_host");
$KEYAPI_RETAILCRM = COption::GetOptionString("intaro.retailcrm", "api_key");
$client = new \RetailCrm\ApiClient($URL_RETAILCRM, $KEYAPI_RETAILCRM);

$arFilter = Array("IBLOCK_ID" => 11, "ACTIVE" => "Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false);
$allPrices = [];
while($ob = $res->GetNextElement())
{
    $arFields = $ob->GetFields();
    $arProps = $ob->GetProperties();
    
    // $ID = $arFields['ID'];
    $xmlId = $arFields['XML_ID'];
    $vip = $arProps['TSENA_VIP']['VALUE'];
    $dealer = $arProps['TSENA_DILER']['VALUE'];
    $opt = $arProps['TSENA_KR_OPT']['VALUE'];
    
    $allPrices[] = [
        'xmlId' => $xmlId,
        'prices' => [
            ['code' => 'vip', 'price' => $vip],
            ['code' => 'dealer', 'price' => $dealer],
            ['code' => 'opt', 'price' => $opt],
        ],
    ];
}

$allPrices = array_chunk($allPrices, 250);
foreach ($allPrices as $chunk) {
    $response = $client->storePricesUpload($chunk);
    var_dump($response);
}

echo "Done";