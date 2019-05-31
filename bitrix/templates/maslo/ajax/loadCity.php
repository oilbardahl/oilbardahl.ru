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
	&& CModule::IncludeModule("iblock") && CModule::IncludeModule("sale") && CModule::IncludeModule("catalog") && $_REQUEST["action-main"]=="getCity" && isset($_REQUEST["name_city"])){
		$listString = '';
		$db_vars = CSaleLocation::GetList(
	        array(
	                "SORT" => "ASC",
	                "COUNTRY_NAME_LANG" => "ASC",
	                "CITY_NAME_LANG" => "ASC"
	            ),
	        array("%CITY_NAME" => utf8win1251($_REQUEST["name_city"]),"LID" => LANGUAGE_ID),
	        false,
	        false,
	        array()
	    );
	   $fisrt = false;

	   while ($vars = $db_vars->Fetch()):
		       if (!$fisrt):  $fisrt = true; $listString='<ul>'; endif;
		       $stringLi=$vars["CITY_NAME"];
		       //if ($vars["REGION_NAME"]): $stringLi.= " / ".$vars["REGION_NAME"]; endif;
		       //if ($vars["COUNTRY_NAME"]): $stringLi.= " / ".$vars["COUNTRY_NAME"]; endif;
		       $listString.="<li attr_city_id='".$vars['ID']."' onclick='selectCitySetOrder(this)'>".$stringLi."</li>";
	   endwhile;
	   if ($fisrt): $listString.='</ul>'; endif;

	   die(ResponseMsg($calcback, "request", array('html'=> iconv('CP1251','UTF-8',$listString))));
}
die(ResponseMsg($calcback, "request", array('html'=> '')));
?>