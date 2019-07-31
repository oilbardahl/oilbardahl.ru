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

if ($response->isSuccessful()) {
    $orderId = $response['id'];
    // var_dump($response);
    
    $client->ordersFixExternalIds([['id' => $orderId, 'externalId' => $orderId . 'A']]);
    // var_dump($response);
    
    CustomGA::sendOrderSuccessEvent2ua($orderId . 'A');
}

function is_phone($phone) {
    if (strpos($phone, '@') !== false)
        return false;
    
    $phone = preg_replace('/\D/', '', $phone);
    if (strlen($phone) < 10)
        return false;
    
    return true;
}


function gaParseCookie() { 
    if (isset($_COOKIE['_ga'])) { 
        list($version,$domainDepth, $cid1, $cid2) = preg_split('[\.]', $_COOKIE["_ga"],4); 

        $contents = array(
                            'version' => $version,
                            'domainDepth' => $domainDepth, 
                            'cid' => $cid1.'.'.$cid2
                         ); 
        $cid = $contents['cid'];
    }
  
    
    return $cid;
}

class CustomGA
{
    private static function sendEvent2ua($ec,$ea,$el,$UA,$cid)
    { 
        $url="http://www.google-analytics.com/collect";
        $data = array( 
          'v' => 1, 
          'tid' => $UA, 
          'cid' => $cid, 
          't' => 'event',
          'ec' => $ec,
          'ea' => $ea,
          'el' => $el
        );
        
        $content = http_build_query($data); // The body of the post must include exactly 1 URI encoded payload and must be no longer than 8192 bytes. See http_build_query.
        $content = utf8_encode($content); // The payload must be UTF-8 encoded.
        $user_agent = 'RoboDigital/1.0'; // Throwing in a user agent just for good measure.
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_USERAGENT, $user_agent);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-type: application/x-www-form-urlencoded'));
        curl_setopt($ch,CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_1);
        curl_setopt($ch,CURLOPT_POST, TRUE);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $content);
        curl_exec($ch);
        curl_close($ch);
    }
    
    // отправка в Google Analytics события order:success
    public static function sendOrderSuccessEvent2ua($orderId) {
        $ua_client_id = gaParseCookie();
        
        $ec = 'Sent_form';
        $ea = 'Success';
        $el = $orderId;
        $UA='UA-66142478-1';
        $cid=$ua_client_id;
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/retailcrm/ga-log.txt', $orderId . ' : ' . $cid . PHP_EOL, FILE_APPEND);
        self::sendEvent2ua($ec,$ea,$el,$UA,$cid);
    }
    
    // если нужно добавить другие события, создаём функции для отправки, аналогичные sendOrderSuccessEvent2ua
}