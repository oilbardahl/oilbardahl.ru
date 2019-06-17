<?php

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