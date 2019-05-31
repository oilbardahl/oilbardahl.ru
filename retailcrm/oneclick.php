<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include.php';

if (!CModule::IncludeModule("intaro.retailcrm"))
    die();

$name = $_REQUEST['Ваше_имя'];
$phone = $_REQUEST['Телефон'];
$comment = $_REQUEST['Дополнительная_информация'];
$productId = $_REQUEST['productId'];
$productName = $_REQUEST['productName'];
$productPrice = $_REQUEST['productPrice'];

if (!$name || !$phone || !$productId)
    die();

$URL_RETAILCRM = COption::GetOptionString("intaro.retailcrm", "api_host");
$KEYAPI_RETAILCRM = COption::GetOptionString("intaro.retailcrm", "api_key");
$client = new \RetailCrm\ApiClient($URL_RETAILCRM, $KEYAPI_RETAILCRM);

$items = [[
    'initialPrice' => $productPrice,
    'productName' => $productName,
    'offer' => [
        'externalId' => $productId
    ]
]];

$order = [
    'orderMethod' =>  'one-click',
    'firstName' => $name,
    'phone' => $phone,
    'customerComment' => $comment,
    'items' => $items,
];

if (!is_phone($phone)) {
    $order['customerComment'] .= "\n\nВ поле телефон ввели: " . $phone;
    unset($order['phone']);
}


$response = $client->ordersCreate($order);
var_dump($order);
var_dump($response);

function is_phone($phone) {
    if (strpos($phone, '@') !== false)
        return false;
    
    $phone = preg_replace('/\D/', '', $phone);
    if (strlen($phone) < 10)
        return false;
    
    return true;
}