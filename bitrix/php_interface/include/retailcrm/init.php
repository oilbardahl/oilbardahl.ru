<?php

$GLOBALS['RX_PERSONAL_DISCOUNT_USER_GROUPS'] = [
    25 => 5,
    26 => 6,
    27 => 7,
    28 => 8,
    29 => 9,
    30 => 10,
];

function retailCrmBeforeOrderSend($order, $arFields)
{
    foreach ($arFields['PROPS']['properties'] as $prop) {
        switch ($prop['CODE']) {
            case 'CLIENT_NAME':
                $order['firstName'] = $prop['VALUE'][0];
                break;
            case 'CLIENT_PATR':
                $order['lastName'] = $prop['VALUE'][0];
                break;
        }
    }
    
    if ($order['delivery']['code'] == 'dostavka-dpd') {
        $order['delivery']['data']['price'] = $order['delivery']['cost'];
    }

    return $order;
    //либо return false; и тогда данные отправлены в систему не будут
}

if(!function_exists('getPropertyByCode')) {
    function getPropertyByCode($propertyCollection, $code)
    {
        foreach ($propertyCollection as $property)
            {
                if ($property->getField('CODE') == $code)
                    {
                        return $property;
                    }
            }
    }
}

function retailCrmAfterOrderSave($order)
{
    $orderId = $order['externalId'];
    
    if ($orderId) {
        $fullAddress1 = \Bitrix\Main\Text\Encoding::convertEncoding($order['customFields']['delivery_address'], 'UTF-8', 'CP1251');
        $orderB = \Bitrix\Sale\Order::load($orderId);
        $propertyCollection = $orderB->getPropertyCollection();
        $prop = getPropertyByCode($propertyCollection, 'SINGLE_LINE_ADDRESS');
        $fullAddress2 = $prop->getValue();
        if ($fullAddress1 != $fullAddress2) {
            $prop->setValue($fullAddress1);
            $orderB->save();
        }
    }
    
    return $order;
}

// makcrx: изменение пользовательской группы при изменении накопительной скидки в ритейле
function retailCrmAfterCustomerSave($customer)
{
    $customerId = $customer['externalId'];
    if (!empty($customerId) && is_numeric($customerId)) {
                    
            // накопительная скидка
            if (CModule::IncludeModule("intaro.retailcrm")) {
                $api_host = COption::GetOptionString("intaro.retailcrm", "api_host");
                $api_key = COption::GetOptionString("intaro.retailcrm", "api_key");
                $client = new \RetailCrm\ApiClient($api_host, $api_key);
                
                $response = $client->customersGet($customerId);
                
                if ($response->isSuccessful()) {
                    $customer = $response->customer;
                }
            }
            
            $discountCode = $customer['customFields']['personal_discount'];
            $PERSONAL_DISCOUNT_USER_GROUPS = array_flip($GLOBALS['RX_PERSONAL_DISCOUNT_USER_GROUPS']);
            
            if (array_key_exists($discountCode, $PERSONAL_DISCOUNT_USER_GROUPS)) {
                $discountUserGroup = $PERSONAL_DISCOUNT_USER_GROUPS[$discountCode];
                
                $currentUserGroups = CUser::GetUserGroup($customerId);
                $currentUserGroups = array_diff($currentUserGroups, array_values($PERSONAL_DISCOUNT_USER_GROUPS));
                $currentUserGroups[] = $discountUserGroup;
                
                $res = CUser::SetUserGroup($customerId, $currentUserGroups);
            }
    }
}