<?require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");?>
<?
function ResponseMsg($callBack, $type , $articles = array()) {
    return ($callBack) ? $callBack."(".json_encode(
            array(
                "type" => $type,
                "items" => $articles,
            )).")" : json_encode(
        array(
            "type" => $type,
            "items" => $articles,
        ));
}
$calcback = (isset($_GET["callback"]) ?  $_GET["callback"] : "");
$APPLICATION->RestartBuffer();

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH']==='XMLHttpRequest'
	&& CModule::IncludeModule("iblock") && CModule::IncludeModule("sale") && CModule::IncludeModule("catalog") && $_REQUEST["action-main"]=="coupon" && isset($_REQUEST["email"])){
	   $connection = Bitrix\Main\Application::getConnection();
	   $cnt = $connection->queryScalar("SELECT count(ID) FROM b_catalog_discount_coupon WHERE ACTIVE='Y' AND DATE_APPLY IS NULL AND ONE_TIME='O' AND DESCRIPTION IS NULL");
       if ($cnt>0){       	  $userOrder = $connection->queryScalar("SELECT count(ID) FROM b_user WHERE EMAIL='".$_REQUEST["email"]."' AND ID IN (select USER_ID from b_sale_fuser)");
       	  if ($userOrder>0){
             die(ResponseMsg($calcback, "user_have", array()));       	  } else {       	    if (isset($_SESSION['check_coupon_user'])){            	die(ResponseMsg($calcback, "you_have_coupon", array()));
       	    } else {
       	    	$couponTxt = '';
       	    	$recordset = $connection->query("SELECT COUPON FROM b_catalog_discount_coupon WHERE ACTIVE='Y' AND DATE_APPLY IS NULL AND ONE_TIME='O' AND DESCRIPTION IS NULL LIMIT 0,1");
				while ($record = $recordset->fetch()){
				    $couponTxt = $record['COUPON'];
				}                $arFields = Array(
			        "EMAIL" =>  $_REQUEST['email'],
			        "COUPON" => $couponTxt
			    );
			    CEvent::Send('COUPON_SEND', SITE_ID, $arFields);
			    CAgent::CheckAgents();
			    $_SESSION['check_coupon_user'] = TRUE;
			    $connection->queryExecute("UPDATE b_catalog_discount_coupon SET DESCRIPTION='CHECK' WHERE COUPON='".$couponTxt."'");
			    die(ResponseMsg($calcback, "send_coupon", array('coupon'=>$couponTxt)));
       	    }
       	  }
       } else {            die(ResponseMsg($calcback, "not_coupon", array()));
       }
}
?>