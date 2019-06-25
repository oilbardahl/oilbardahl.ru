<?php
    
    // $lock_file = __DIR__ . '/orderAgent.lock';
    
    // if (file_exists($lock_file)) {
        // $time = file_get_contents($lock_file);
        // $period = (string)(time() - strtotime($time));
        // if ($period < 30*60) {
            // echo "Error: script already run for " . $period . " seconds.";
            // die();
        // }
    // }
    
    try {
        // file_put_contents($lock_file, date("Y-m-d H:i:s"));
        
        if (!$_SERVER['DOCUMENT_ROOT'])
            $_SERVER['DOCUMENT_ROOT'] = __DIR__ . '/..';
        require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include.php';
        
        global $APPLICATION, $USER;
        if (!is_object($USER)) {
            $USER = new CUser();
        }
        
        if (!CModule::IncludeModule("intaro.retailcrm")) return 0;
        
        RetailCrmHistory::customerHistory();
        echo "customer history done\n";
        RetailCrmHistory::orderHistory();
        echo "order history done\n";
        RCrmActions::uploadOrdersAgent();
        echo "new orders from bitrix done\n";
        
    } catch (Exception $e) {
        echo $e->getMessage();
        echo $e->getTraceAsString();
        mail ( 'e7a5f7t8f4l5k2f5@analitika-online.slack.com' , '[whitecatnsk.ru] Ошибка при выгрузке заказов из retailcrm' , '[whitecatnsk.ru] Ошибка при выгрузке заказов из retailcrm' . "\n" . $e->getMessage() . "\n" . $e->getTraceAsString());
    }
    
    // unlink($lock_file);
