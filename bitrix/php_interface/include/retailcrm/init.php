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