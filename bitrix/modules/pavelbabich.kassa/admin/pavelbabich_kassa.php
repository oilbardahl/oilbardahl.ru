<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
define("ADMIN_MODULE_NAME", "pavelbabich.kassa");
define("ADMIN_MODULE_ICON", "ICON!");
//IncludeModuleLangFile(__FILE__);
CModule::IncludeModule("pavelbabich.kassa");
$APPLICATION->SetTitle(GetMessage("PKASSA_MAIN_MODULE_PAGE_PKASSA_SETTINGS"));



if (!$USER->IsAdmin()) $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

$aTabs = array();
if ($USER->IsAdmin()) {
    $aTabs = array(
        array("DIV" => "kassasettings0", "TAB" => GetMessage("PKASSA_MAIN_MODULE_PAGE_COMMON_INFO"), "ICON"=>"main_user_edit", "TITLE"=>GetMessage("PKASSA_MAIN_MODULE_PAGE_COMMON_INFO_AND_SETTINGS")),
        array("DIV" => "kassasettings1", "TAB" => GetMessage("PKASSA_MAIN_MODULE_PAGE_LOCAL_SETTINGS"), "ICON"=>"main_user_edit", "TITLE"=>GetMessage("PKASSA_MAIN_MODULE_PAGE_LOCAL_SETTINGS")),
        array("DIV" => "kassasettings2", "TAB" => GetMessage("PKASSA_MAIN_MODULE_PAGE_TAB_EXPORTED_ORDERS"), "ICON"=>"main_user_edit", "TITLE"=>GetMessage("PKASSA_MAIN_MODULE_PAGE_TAB_EXPORTED_ORDERS"))
    );
}
$tabControl = new CAdminTabControl("tabControl", $aTabs);

?>
<?
if ($REQUEST_METHOD == "POST" && ($_REQUEST["save"] || $_REQUEST["get_uuid"]) && check_bitrix_sessid()){
    if($_POST["PKASSAMAIN"]["HANDSELF"]!="Y")$_POST["PKASSAMAIN"]["HANDSELF"]="N";
    if($_POST["PKASSAMAIN"]["AUTOMAT"]!="Y")$_POST["PKASSAMAIN"]["AUTOMAT"]="N";
    PKASSAMainSettings::SaveSettings();
}

if ($REQUEST_METHOD == "POST" && $_REQUEST["get_uuid"] && check_bitrix_sessid()){
    $StoreUUID = PKASSAModuleMain::GetStoreUUID(PKASSAMainSettings::GetSiteSetting("TOKEN"));
}

if(intval($_REQUEST["ID_ORDER"])){
    $ExportOrder = PKASSAModuleMain::GetExportOrder(intval($_REQUEST["ID_ORDER"]));    
    $exportResult = PKASSAModuleMain::ExportToEvotor(PKASSAMainSettings::GetSiteSetting("TOKEN"), PKASSAMainSettings::GetSiteSetting("UUID"), $ExportOrder);
}

require_once ($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/prolog_admin_after.php");
?>
<form method="POST" Action="<?=$APPLICATION->GetCurPage()?>" ENCTYPE="multipart/form-data" name="kassasettings_form">
<?echo bitrix_sessid_post();?>
<?
$tabControl->Begin();


if($USER->IsAdmin()){
    ?>
    <?
    //********************
    // zero tab - about
    //********************
    $tabControl->BeginNextTab();

    ?>
    <tr class="">
        <td colspan="2">
            <?=GetMessage("PKASSA_MAIN_MODULE_PAGE_TAB_COMMON_INFO")?>
            <br/><br/>
            <?if(intval($_REQUEST["ID_ORDER"])){?>
                <div style="border:1px solid #666;color:red;padding:10px;font-weight:600;">
                    <?
                    if(!$ExportOrder){
                       echo GetMessage("PKASSA_MAIN_MODULE_PAGE_ORDER_NOT_FOUND");
                    }else{
                       echo GetMessage("PKASSA_MAIN_MODULE_PAGE_ORDER_NUM").intval($_REQUEST["ID_ORDER"])."<br>";
                       echo GetMessage("PKASSA_MAIN_MODULE_PAGE_LOAD_RESULT")." $exportResult";
                    }
                    if($_REQUEST["EXPORT"]=="Y"){
                        $APPLICATION->RestartBuffer();
                        if($exportResult)echo strip_tags($exportResult);
                            else echo GetMessage("PKASSA_MAIN_MODULE_EXPORT_ERROR");
                        die();
                    }
                    ?>
                </div>
            <?}?>
            <?
            $arPaydOrders = array();
            $rsOrders = CSaleOrder::GetList(array(), Array("PAYED" => "Y"));
            while ($arOrders = $rsOrders->Fetch())$arPaydOrders[]=$arOrders["ID"];
            
            //echo "NOW_PAID=".implode(";",$arPaydOrders)."<br>";
            //echo "EX_PAID=".PKASSAMainSettings::GetSiteSetting("PAIDORDERS");
            //PKASSAModuleMain::SendPaydOrdersToEvotor();
            ?>
        </td>
    </tr>
    <tr><td>&nbsp;</td></tr>
    <tr>
        <td>
        <h3><?=GetMessage("PKASSA_MAIN_MODULE_PAYED_ORDERS_EXPORT")?>:</h3>
        <?if($_REQUEST["table_id"]=="tbl_orders_to_export")$APPLICATION->RestartBuffer();?>
        <?
        $ExportedOrders = explode(";",PKASSAMainSettings::GetSiteSetting("EXPORTEDORDERS"));
        $ExPaidOrders = explode(";",PKASSAMainSettings::GetSiteSetting("PAIDORDERS"));
        
        $sTableID = "tbl_orders_to_export"; // ID таблицы
        $oSort = new CAdminSorting($sTableID, "ID", "desc"); // объект сортировки
        $lAdmin = new CAdminList($sTableID, $oSort); // основной объект списка
        
        if($_REQUEST["SIZEN_1"])$sizenP = intval($_REQUEST["SIZEN_1"]);
            else $sizenP = 10;
        $SelectedPaySystems = COption::GetOptionString("pavelbabich.kassa", "PKASSAPAYSYSTEMS");
        if($SelectedPaySystems)$arSelectedPaySystems = explode(";",$SelectedPaySystems);
            else $arSelectedPaySystems = array();
        $rsOrders = CSaleOrder::GetList(array("ID"=>"desc"), Array("PAYED" => "Y", "PAY_SYSTEM_ID" => $arSelectedPaySystems, "!ID"=>array_merge($ExportedOrders,$ExPaidOrders)),false,Array("nPageSize"=>$sizenP),array("ID","USER_ID","DATE_INSERT","PRICE","STATUS_ID"));
        // преобразуем список в экземпл€р класса CAdminResult
        $rsOrders = new CAdminResult($rsOrders, $sTableID);

        // аналогично CDBResult инициализируем постраничную навигацию.
        $rsOrders->NavStart();

        // отправим вывод переключател€ страниц в основной объект $lAdmin
        $lAdmin->NavText($rsOrders->GetNavPrint(GetMessage("PKASSA_MAIN_MODULE_ORDERS")));
        
        $lAdmin->AddHeaders(array(
            array("id"=>"ID", "content"=>"ID", "sort"=>"id", "default"=>true),
            array("id"=>"USER_ID", "content"=>GetMessage("PKASSA_MAIN_MODULE_USER"), "sort"=>"name", "default"=>true),
            array("id"=>"DATE_INSERT", "content"=>GetMessage("PKASSA_MAIN_MODULE_ORDER_DATE"), "sort"=>"date_insert", "default"=>true),
            array("id"=>"PRICE", "content"=>GetMessage("PKASSA_MAIN_MODULE_ORDER_SUMM"), "sort"=>"summ", "default"=>true),
            array("id"=>"STATUS_ID", "content"=>GetMessage("PKASSA_MAIN_MODULE_ORDER_STATUS"), "sort"=>"status", "default"  =>true),
            array("id"=>"GOODS", "content"=>GetMessage("PKASSA_MAIN_MODULE_ORDER_GOODS"), "sort"=>"goods", "default"=>true),
            array("id"=>"EXPORT", "content"=>GetMessage("PKASSA_MAIN_MODULE_ORDER_EXPORT"), "sort"=>"export", "default"=>true),
        ));
        
        while($arRes = $rsOrders->NavNext(true, "f_")){
            $row =& $lAdmin->AddRow($f_ID, $arRes);
            
            //USER
            $rsUser = CUser::GetByID($f_USER_ID);
            $arUser = $rsUser->Fetch();
            $row->AddInputField("USER_ID", array("size"=>20));
            $row->AddViewField("USER_ID", '<a href="user_edit.php?ID='.$f_USER_ID.'&lang='.LANG.'">'.$arUser["EMAIL"].'</a>');
            
            //Summ
            $row->AddViewField("PRICE", number_format($f_PRICE, 2,"."," "));
            
            //Goods
            $arBasketItems = array();
            $dbBasketItems = CSaleBasket::GetList(array(), array("ORDER_ID" => $f_ID),false,false,array("PRODUCT_ID", "QUANTITY"));
            while($arItem = $dbBasketItems->Fetch()){
                $Element = CIBlockElement::GetByID($arItem["PRODUCT_ID"])->GetNext();
                $arBasketItems[] = $Element['NAME'];
            }
            $row->AddViewField("GOODS", implode("; ", $arBasketItems));
            
            //¬ыгрузка
            $row->AddViewField("EXPORT", '<a href="?ID_ORDER='.$f_ID.'">'.GetMessage("PKASSA_MAIN_MODULE_ORDER_EXPORT").'</a>');
            
            //Status
            $arStatus = CSaleStatus::GetByID($f_STATUS_ID);
            $row->AddViewField("STATUS_ID", "(".$f_STATUS_ID.") ".$arStatus["NAME"]);
        }
        
        // резюме таблицы
        $lAdmin->AddFooter(array(
            array("title"=>GetMessage("PKASSA_MAIN_MODULE_EXPORTED_ORDERS"), "value"=>8), // кол-во элементов
            array("counter"=>true, "title"=>GetMessage("PKASSA_MAIN_MODULE_T_HEADER"), "value"=>"0"), // счетчик выбранных элементов
        ));
        
        // выведем таблицу списка элементов
        
        $lAdmin->DisplayList();
        ?>
        <?if($_REQUEST["table_id"]=="tbl_orders_to_export")die();?>
        </td>
    </tr>

    <?
    //********************
    // first tab - SETTINGS
    //********************
    $tabControl->BeginNextTab();

    ?>
    <tr class="">
        <td colspan="2">
            <?=GetMessage("PKASSA_MAIN_MODULE_PAGE_TAB_LESSTRIX")?>
            <br/><br/></td>
    </tr>
    <tr>
        <td width="40%">TOKEN:</td>
        <td width="60%">
            <input type="text" name="PKASSAMAIN[TOKEN]" value="<?=PKASSAMainSettings::GetSiteSetting("TOKEN")?>" size="60"/>
        </td>
    </tr>
    <tr>
        <td width="40%"></td>
        <td width="60%">
            <input type="submit" name="get_uuid" value="<?=GetMessage("PKASSA_MAIN_MODULE_GET_STORE_UID")?>" size="60"/>
            <?if($StoreUUID){?>
                <?if(is_object($StoreUUID) && isset($StoreUUID->errors)){?>
                    <p style="color:red;"><?=GetMessage("PKASSA_MAIN_MODULE_PAGE_ERROR")?>: <?print_r($StoreUUID->errors[0])?></p>
                <?}elseif(isset($StoreUUID[0]) && is_object($StoreUUID[0])){?>
                    <table border="1">
                        <tr><td><?=GetMessage("PKASSA_MAIN_MODULE_PAGE_STORE_NAME")?></td><td>StoreUUID</td></tr>
                        <?foreach($StoreUUID as $rsStore){?><tr><td><?=((LANG_CHARSET=="windows-1251")?iconv('utf-8','cp1251',$rsStore->name):$rsStore->name)?></td><td><?=$rsStore->uuid?></td></tr><?}?>
                    </table>
                    <?$getUuid = $StoreUUID[0]->uuid?>
                <?}else{?>
                    <p style="color:red;"><?=GetMessage("PKASSA_MAIN_MODULE_PAGE_UUID_ERROR")?></p>
                <?}?>
            <?}?>
        </td>
    </tr>
    <tr>
        <td width="40%"><?=GetMessage("PKASSA_MAIN_MODULE_UUID")?>:</td>
        <td width="60%">
            <input type="text" name="PKASSAMAIN[UUID]" value="<?=PKASSAMainSettings::GetSiteSetting("UUID")?PKASSAMainSettings::GetSiteSetting("UUID"):$getUuid?>" size="60"/>
        </td>
    </tr>
    <tr>
        <td width="40%"><?=GetMessage("PKASSA_MAIN_MODULE_HANDSELF")?>:</td>
        <td width="60%">
            <input type="checkbox" name="PKASSAMAIN[HANDSELF]" value="Y" <?=((PKASSAMainSettings::GetSiteSetting("HANDSELF")=="Y")?"checked='checked'":"")?>/>
        </td>
    </tr>
    <tr>
        <td width="40%"><?=GetMessage("PKASSA_MAIN_MODULE_AUTOMAT")?>:</td>
        <td width="60%">
            <input type="checkbox" name="PKASSAMAIN[AUTOMAT]" value="Y" <?=((PKASSAMainSettings::GetSiteSetting("AUTOMAT")=="Y")?"checked='checked'":"")?>/>
        </td>
    </tr>
    <tr>
        <td width="40%"><?=GetMessage("PKASSA_MAIN_MODULE_PAYSYSTEMS")?>:</td>
        <td width="60%">
            <select multiple size="6" name="PAY_SYSTEM_ID[]">
                <?
                if($_REQUEST["PAY_SYSTEM_ID"])COption::SetOptionString("pavelbabich.kassa", "PKASSAPAYSYSTEMS", implode(";",$_REQUEST["PAY_SYSTEM_ID"]));
                $SelectedPaySystems = COption::GetOptionString("pavelbabich.kassa", "PKASSAPAYSYSTEMS");
                if($SelectedPaySystems)$arSelectedPaySystems = explode(";",$SelectedPaySystems);
                    else $arSelectedPaySystems = array();
                $rsPaySystems = CSalePaySystem::GetList($arOrder = Array("SORT"=>"ASC", "PSA_NAME"=>"ASC"), Array("ACTIVE"=>"Y"));
                while ($arPaySystem = $rsPaySystems->Fetch())
                {?>
                    <option value="<?=$arPaySystem["ID"]?>" <?if(in_array($arPaySystem["ID"],$arSelectedPaySystems)){?>selected="selected"<?}?>><?=$arPaySystem["NAME"]?></option>
                <?}?>
            </select>
        </td>
    </tr>
    <tr>
        <td width="40%"><?=GetMessage("PKASSA_MAIN_MODULE_NAMEPROP")?>:</td>
        <td width="60%">
            <select name="NAMEPROP">
                <option value=""><?=GetMessage("PKASSA_MAIN_MODULE_NOTSELECTED")?></option>
                <?if(isset($_REQUEST["NAMEPROP"]))COption::SetOptionString("pavelbabich.kassa", "PKASSANAMEPROP", $_REQUEST["NAMEPROP"]);
                $SelectedNameProp = COption::GetOptionString("pavelbabich.kassa", "PKASSANAMEPROP");
                $rsProperties = CSaleOrderProps::GetList(array("SORT" => "ASC"), array('ACTIVE'=>'Y'), false, false, array('ID', 'NAME', 'CODE'));
                while($arProp = $rsProperties->GetNext())
                {?>
                    <option value="<?=$arProp["ID"]?>" <?if($arProp["ID"]==$SelectedNameProp){?>selected="selected"<?}?>><?=$arProp["NAME"]?> (<?=$arProp["CODE"]?>)</option>
                <?}?>
            </select>
        </td>
    </tr>
    <tr>
        <td width="40%"><?=GetMessage("PKASSA_MAIN_MODULE_EMAILPROP")?>:</td>
        <td width="60%">
            <select name="EMAILPROP">
                <option value=""><?=GetMessage("PKASSA_MAIN_MODULE_NOTSELECTED")?></option>
                <?if(isset($_REQUEST["EMAILPROP"]))COption::SetOptionString("pavelbabich.kassa", "PKASSAEMAILPROP", $_REQUEST["EMAILPROP"]);
                $SelectedNameProp = COption::GetOptionString("pavelbabich.kassa", "PKASSAEMAILPROP");
                $rsProperties = CSaleOrderProps::GetList(array("SORT" => "ASC"), array('ACTIVE'=>'Y'), false, false, array('ID', 'NAME', 'CODE'));
                while($arProp = $rsProperties->GetNext())
                {?>
                    <option value="<?=$arProp["ID"]?>" <?if($arProp["ID"]==$SelectedNameProp){?>selected="selected"<?}?>><?=$arProp["NAME"]?> (<?=$arProp["CODE"]?>)</option>
                <?}?>
            </select>
        </td>
    </tr>
    <tr>
        <td width="40%"><?=GetMessage("PKASSA_MAIN_MODULE_PHONEPROP")?>:</td>
        <td width="60%">
            <select name="PHONEPROP">
                <option value=""><?=GetMessage("PKASSA_MAIN_MODULE_NOTSELECTED")?></option>
                <?if(isset($_REQUEST["PHONEPROP"]))COption::SetOptionString("pavelbabich.kassa", "PKASSAPHONEPROP", $_REQUEST["PHONEPROP"]);
                $SelectedNameProp = COption::GetOptionString("pavelbabich.kassa", "PKASSAPHONEPROP");
                $rsProperties = CSaleOrderProps::GetList(array("SORT" => "ASC"), array('ACTIVE'=>'Y'), false, false, array('ID', 'NAME', 'CODE'));
                while($arProp = $rsProperties->GetNext()){?>
                    <option value="<?=$arProp["ID"]?>" <?if($arProp["ID"]==$SelectedNameProp){?>selected="selected"<?}?>><?=$arProp["NAME"]?> (<?=$arProp["CODE"]?>)</option>
                <?}?>
            </select>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <p style="color:red;"><?=GetMessage("PKASSA_MAIN_MODULE_PAGE_AGENT")?></p>
        </td>
    </tr>
    
    <?
    //********************
    // Exported Orders
    //********************
    $tabControl->BeginNextTab();
    ?>
    <tr class="">
        <td colspan="2">
        <?if($_REQUEST["table_id"]=="tbl_rubric")$APPLICATION->RestartBuffer();?>
        <?
        $ExportedOrders = explode(";",PKASSAMainSettings::GetSiteSetting("EXPORTEDORDERS"));
        $sTableID = "tbl_rubric"; // ID таблицы
        $oSort = new CAdminSorting($sTableID, "ID", "desc"); // объект сортировки
        $lAdmin = new CAdminList($sTableID, $oSort); // основной объект списка
        
        if($_REQUEST["SIZEN_1"])$sizenP = intval($_REQUEST["SIZEN_1"]);
            else $sizenP = 10;
        $rsOrders = CSaleOrder::GetList(array("ID"=>"desc"), Array("ID" => $ExportedOrders),false,Array("nPageSize"=>$sizenP),array("ID","USER_ID","DATE_INSERT","PRICE","STATUS_ID"));
        // преобразуем список в экземпл€р класса CAdminResult
        $rsOrders = new CAdminResult($rsOrders, $sTableID);

        // аналогично CDBResult инициализируем постраничную навигацию.
        $rsOrders->NavStart();

        // отправим вывод переключател€ страниц в основной объект $lAdmin
        $lAdmin->NavText($rsOrders->GetNavPrint(GetMessage("PKASSA_MAIN_MODULE_ORDERS")));
        
        $lAdmin->AddHeaders(array(
            array("id"=>"ID", "content"=>"ID", "sort"=>"id", "default"=>true),
            array("id"=>"USER_ID", "content"=>GetMessage("PKASSA_MAIN_MODULE_USER"), "sort"=>"name", "default"=>true),
            array("id"=>"DATE_INSERT", "content"=>GetMessage("PKASSA_MAIN_MODULE_ORDER_DATE"), "sort"=>"date_insert", "default"=>true),
            array("id"=>"PRICE", "content"=>GetMessage("PKASSA_MAIN_MODULE_ORDER_SUMM"), "sort"=>"summ", "default"=>true),
            array("id"=>"STATUS_ID", "content"=>GetMessage("PKASSA_MAIN_MODULE_ORDER_STATUS"), "sort"=>"status", "default"  =>true),
            array("id"=>"GOODS", "content"=>GetMessage("PKASSA_MAIN_MODULE_ORDER_GOODS"), "sort"=>"goods", "default"=>true),
        ));
        
        while($arRes = $rsOrders->NavNext(true, "f_")){
            $row =& $lAdmin->AddRow($f_ID, $arRes);
            
            //USER
            $rsUser = CUser::GetByID($f_USER_ID);
            $arUser = $rsUser->Fetch();
            $row->AddInputField("USER_ID", array("size"=>20));
            $row->AddViewField("USER_ID", '<a href="user_edit.php?ID='.$f_USER_ID.'&lang='.LANG.'">'.$arUser["EMAIL"].'</a>');
            
            //Summ
            $row->AddViewField("PRICE", number_format($f_PRICE, 2,"."," "));
            
            //Goods
            $arBasketItems = array();
            $dbBasketItems = CSaleBasket::GetList(array(), array("ORDER_ID" => $f_ID),false,false,array("PRODUCT_ID", "QUANTITY"));
            while($arItem = $dbBasketItems->Fetch()){
                $Element = CIBlockElement::GetByID($arItem["PRODUCT_ID"])->GetNext();
                $arBasketItems[] = $Element['NAME'];
            }
            $row->AddViewField("GOODS", implode("; ", $arBasketItems));
            
            //Status
            $arStatus = CSaleStatus::GetByID($f_STATUS_ID);
            $row->AddViewField("STATUS_ID", "(".$f_STATUS_ID.") ".$arStatus["NAME"]);
        }
        
        // резюме таблицы
        $lAdmin->AddFooter(array(
            array("title"=>GetMessage("PKASSA_MAIN_MODULE_EXPORTED_ORDERS"), "value"=>8), // кол-во элементов
            array("counter"=>true, "title"=>GetMessage("PKASSA_MAIN_MODULE_T_HEADER"), "value"=>"0"), // счетчик выбранных элементов
        ));
        
        // выведем таблицу списка элементов
        
        $lAdmin->DisplayList();
        ?>
        <?if($_REQUEST["table_id"]=="tbl_rubric")die();?>
        </td>
    </tr>
    <tr class="">
        <td colspan="2">
         </td>
    </tr>
<?
}

$tabControl->Buttons(
    array(
    )
);

?>
<?
$tabControl->End();

if ($ex = $APPLICATION->GetException()) {
    $message = new CAdminMessage(GetMessage("rub_save_error"), $ex);
    echo $message->Show();
}
?>
<?
$tabControl->ShowWarnings("kassasettings_form", $message);
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>