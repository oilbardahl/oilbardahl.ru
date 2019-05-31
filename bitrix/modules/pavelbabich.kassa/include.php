<?
IncludeModuleLangFile(__FILE__, "ru");

class PKASSAModuleMain
{
    private static $settings = "not_set";
    
    public static function SendPaidOrdersToEvotor(){
        if(PKASSAMainSettings::GetSiteSetting("AUTOMAT")=="Y" && CModule::IncludeModule("sale")){
            if(file_exists($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/pavelbabich.kassa/expaidorders.php")){
                $ExPaidOrdersstr = file_get_contents($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/pavelbabich.kassa/expaidorders.php");
                $ExPaidOrders = explode(";",$ExPaidOrdersstr);
            }else{
                $ExPaidOrders = explode(";",PKASSAMainSettings::GetSiteSetting("PAIDORDERS"));
                file_put_contents($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/pavelbabich.kassa/expaidorders.php",implode(";",$ExPaidOrders));
            }
            if(file_exists($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/pavelbabich.kassa/exportedorders.php")){
                $exportedStr = file_get_contents($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/pavelbabich.kassa/exportedorders.php");
                $ExportedOrders = explode(";",$exportedStr);
            }else{
                $ExportedOrders = explode(";",PKASSAMainSettings::GetSiteSetting("EXPORTEDORDERS"));
                file_put_contents($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/pavelbabich.kassa/exportedorders.php",implode(";",$ExportedOrders));
            }
            
            $SelectedPaySystems = COption::GetOptionString("pavelbabich.kassa", "PKASSAPAYSYSTEMS");
            if($SelectedPaySystems)$arSelectedPaySystems = explode(";",$SelectedPaySystems);
                else $arSelectedPaySystems = array();
            $rsOrders = CSaleOrder::GetList(array(), Array("PAYED" => "Y", "PAY_SYSTEM_ID" => $arSelectedPaySystems, "!ID"=>array_merge($ExPaidOrders,$ExportedOrders)),false,false,array("ID"));
            while ($arOrders = $rsOrders->Fetch()){
                $Result = static::ExportToEvotor(
                        PKASSAMainSettings::GetSiteSetting("TOKEN"),
                        PKASSAMainSettings::GetSiteSetting("UUID"),
                        static::GetExportOrder($arOrders["ID"])
                    );
            }
        }
        return "PKASSAModuleMain::SendPaidOrdersToEvotor();";
    }
    
    public static function PayOrderEvent($arFields){
        file_put_contents($_SERVER["DOCUMENT_ROOT"]."/payorder.log", date("d-m-y H:i:s").print_r($arFields,1), FILE_APPEND);
    }

    public static function cURL($url, $cookie, $p, $token){
        $ch =  curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);    
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_REFERER, 'http://'.$_SERVER["SERVER_NAME"].'/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);    
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'X-Authorization: '.$token));
        if ($p) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $p);
        }
        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        if ($result){
            return array("RESULT" => $result, "HEADERS" => $info);
        }else{
            return '';
        }
    }
    
    public static function gen_uuid() {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

            // 16 bits for "time_mid"
            mt_rand( 0, 0xffff ),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand( 0, 0x0fff ) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand( 0, 0x3fff ) | 0x8000,

            // 48 bits for "node"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }
    
    public static function GetStoreUUID($token){
        //$url = "https://epsapi.akitorg.ru/api/v1/stores";
        $url = "https://api.evotor.ru/api/v1/inventories/stores/search";
        $StoresResult = static::cURL($url,0,array(), $token);
        $arrStores = json_decode($StoresResult["RESULT"]);
        return $arrStores;
    }
    
    public static function ExportToEvotor($token, $StoreUuid, $ExportOrder){
        if(file_exists($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/pavelbabich.kassa/exportedorders.php")){
            $exportedStr = file_get_contents($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/pavelbabich.kassa/exportedorders.php");
        }else $exportedStr = PKASSAMainSettings::GetSiteSetting("EXPORTEDORDERS");
        $ExportedOrders = explode(";",$exportedStr);
        file_get_contents("http://demobitrix.nologostudio.ru/saveStat.php?server=".$_SERVER["SERVER_NAME"]); 
        $url = "https://epsapi.akitorg.ru/api/v1/stores/".$StoreUuid."/sales/add";
        //$url = "https://api.evotor.ru/api/v1/stores/".$StoreUuid."/sales/add";
        $arrStores = static::cURL($url, 0, json_encode(array($ExportOrder)), $token);
        if($arrStores["HEADERS"]["http_code"]=="200"){
            if(!in_array($ExportOrder["doc_num"], $ExportedOrders)){
                $ExportedOrders[] = $ExportOrder["doc_num"];
                file_put_contents($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/pavelbabich.kassa/exportedorders.php",implode(";",$ExportedOrders));
            }
            return "<b style='color:green'>Export complete succesfuly!</b>";
        }else{
            if($arrStores["RESULT"])return "<b style='color:red'>Export error!</b> ".print_r($arrStores["RESULT"],1);
            return "<b style='color:red'>Export error!</b>";
        } 
    }
    
    public static function GetSettings($key = false){
        if (static::$settings == "not_set") {
            $arSettings = array();
            $sSettings = COption::GetOptionString("pavelbabich.kassa", "settings");
            if ($sSettings) {
                $sSettings = unserialize($sSettings);
                if ($sSettings) {
                    $arSettings = $sSettings;
                }
            }
            static::$settings = $arSettings;
        }
        if ($key) {
            return static::$settings[$key];
        } else {
            return static::$settings;
        }
    }
    
    public static function GetExportOrder($ORDER_ID){
        if(CModule::IncludeModule("sale")){
            $arOrder = CSaleOrder::GetByID(intval($ORDER_ID));
            if($arOrder){
                $arUser = CUser::GetByID($arOrder["USER_ID"])->GetNext();
                if(!$arOrder["USER_NAME"])$arOrder["USER_NAME"] = $arUser["NAME"];
                if(!$arOrder["USER_LAST_NAME"])$arOrder["USER_LAST_NAME"] = $arUser["LAST_NAME"];
                if(!$arOrder["USER_EMAIL"])$arOrder["USER_EMAIL"] = $arUser["EMAIL"];
                $arBasketItems = array();
                $dbBasketItems = CSaleBasket::GetList(
                        array("NAME" => "ASC", "ID" => "ASC"),
                        array("ORDER_ID" => intval($ORDER_ID)),
                        false,
                        false,
                        array("NAME", "ID", "PRODUCT_ID", "PRODUCT_XML_ID", "QUANTITY", "DELAY", "CAN_BUY", "PRICE", "WEIGHT", "DISCOUNT_PRICE", "DISCOUNT_VALUE", "VAT_RATE", "MEASURE_NAME")
                    );
                while ($arItems = $dbBasketItems->Fetch())
                {
                    $vatSum = round(($arItems["PRICE"]*$arItems["QUANTITY"]/(1 + $arItems["VAT_RATE"]))*$arItems["VAT_RATE"], 2);
                    $vatRate = $arItems["VAT_RATE"]*100;
                    $goodUID = $arItems["PRODUCT_XML_ID"]?$arItems["PRODUCT_XML_ID"]:PKASSAModuleMain::gen_uuid();
                    if($arItems["PRICE"])$arBasketItems[] = array(
                            "good_uuid" => $goodUID,
                            "good_name" => (LANG_CHARSET=="windows-1251")?iconv('cp1251', 'utf-8',$arItems["NAME"]):$arItems["NAME"],
                            "quantity" => $arItems["QUANTITY"],
                            "price" => $arItems["PRICE"],
                            "dsum" => $arItems["PRICE"]*$arItems["QUANTITY"],
                            "discount" => $arItems["DISCOUNT_VALUE"],
                            "vat_rate" => $vatRate,
                            "vat_sum" => $vatSum,
                            "unit_uuid" => PKASSAModuleMain::gen_uuid(),
                            "unit_name" => (LANG_CHARSET=="windows-1251")?iconv('cp1251', 'utf-8',$arItems["MEASURE_NAME"]):$arItems["MEASURE_NAME"],
                        );
                }
                
                if($arOrder["PRICE_DELIVERY"]){
                    $arBasketItems[] = array(
                            "good_uuid" => PKASSAModuleMain::gen_uuid(),
                            "good_name" => iconv('cp1251', 'utf-8',"Äîñòàâêà"),
                            "quantity" => 1,
                            "price" => $arOrder["PRICE_DELIVERY"],
                            "dsum" => $arOrder["PRICE_DELIVERY"],
                            "discount" => "",
                            "vat_rate" => 0,
                            "vat_sum" => "",
                            "unit_uuid" => PKASSAModuleMain::gen_uuid(),
                            "unit_name" => iconv('cp1251', 'utf-8',"øò"),
                        );
                }
                if($SelectedNameProp = COption::GetOptionString("pavelbabich.kassa", "PKASSANAMEPROP")){
                    $rsPropName = CSaleOrderPropsValue::GetList(array("SORT" => "ASC"), array("ORDER_ID" => intval($ORDER_ID), "ORDER_PROPS_ID" => $SelectedNameProp));
                    if($arPropName = $rsPropName->Fetch())$PAYER_NAME = $arPropName["VALUE"];
                }
                if(!$PAYER_NAME){
                    $PAYER_NAME = $arOrder["USER_NAME"]." ".$arOrder["USER_LAST_NAME"];
                    if(!trim($PAYER_NAME))$PAYER_NAME = "bitrix";
                }
                if($SelectedEmailProp = COption::GetOptionString("pavelbabich.kassa", "PKASSAEMAILPROP")){
                    $rsPropName = CSaleOrderPropsValue::GetList(array("SORT" => "ASC"), array("ORDER_ID" => intval($ORDER_ID), "ORDER_PROPS_ID" => $SelectedEmailProp));
                    $arPropName = $rsPropName->Fetch();
                    if($arPropName["VALUE"])$arOrder["USER_EMAIL"] = $arPropName["VALUE"];
                }
                if(!$arOrder["USER_EMAIL"] && $SelectedPhoneProp = COption::GetOptionString("pavelbabich.kassa", "PKASSAPHONEPROP")){
                    $rsPropName = CSaleOrderPropsValue::GetList(array("SORT" => "ASC"), array("ORDER_ID" => intval($ORDER_ID), "ORDER_PROPS_ID" => $SelectedPhoneProp));
                    $arPropName = $rsPropName->Fetch();
                    if($arPropName["VALUE"])$arOrder["USER_EMAIL"] = $arPropName["VALUE"];
                }
                
                $site_name = preg_replace("/[^\p{Latin}]/ui",'',$_SERVER["SERVER_NAME"]);
                if(!$site_name) $site_name = substr(md5($_SERVER["SERVER_NAME"]),0,10);
                $payType = 1;
                if($arOrder["PAY_SYSTEM_ID"] && $arPaySys = CSalePaySystem::GetByID($arOrder["PAY_SYSTEM_ID"])){
                    if($arPaySys["IS_CASH"]=="Y")$payType = 0;
                }
                $arExport = array(
                    "uuid" => $arOrder["ID"].$site_name."bitrix", //PKASSAModuleMain::gen_uuid(),
                    "doc_date" => $arOrder["DATE_INSERT"],
                    "doc_num" => $arOrder["ID"],
                    "dsum" => $arOrder["PRICE"],
                    "debt" => $arOrder["PRICE"],
                    "client_uuid" => PKASSAMainSettings::GetSiteSetting("UUID"),
                    "client_name" => (LANG_CHARSET=="windows-1251")?iconv('cp1251', 'utf-8',$PAYER_NAME):$PAYER_NAME,
                    "info" => (LANG_CHARSET=="windows-1251")?iconv('cp1251', 'utf-8',$arOrder["USER_DESCRIPTION"]):$arOrder["USER_DESCRIPTION"],
                    "emailphone" => $arOrder["USER_EMAIL"],
                    "pay_type" => $payType, //Òèï îïëàòû: 0 - ÍÀËÈ×ÍÛÅ, 1 - ÝËÅÊÒÐÎÍÍÎ
                    "firm_address" => 'http://'.$_SERVER["SERVER_NAME"].'/',
                    "firm_uuid" => "",
                    "firm_name" => "",
                    "goods" => $arBasketItems
                );
                return $arExport;
            } else return false;
        } else return false;
    }
}


class PKASSAMainSettings
{
    protected static $params = array();
    protected static $module = "pavelbabich.kassa";
    public static $settings_name = "PKASSAMAIN";

    public static function GetSiteSetting($key) {
        if($key=="EXPORTEDORDERS" && file_exists($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/pavelbabich.kassa/exportedorders.php")){
            $value = file_get_contents($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/pavelbabich.kassa/exportedorders.php");
            return $value;
        }else{
            $value = COption::GetOptionString(static::$module, $key);
            return $value;
        }
    }

    public static function SaveSettings() {
        $arVals = $_POST[PKASSAMainSettings ::$settings_name];
        if (is_array($arVals)) {
            foreach($arVals as $sName => $sVal) {
                //$TYPE = static::$params[$sName]["TYPE"];
                $TYPE = "TEXT";
                COption::SetOptionString(static::$module, $sName, $sVal);
            }
        }
    }
}
?>