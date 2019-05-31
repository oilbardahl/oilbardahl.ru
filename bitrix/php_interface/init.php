<?
require_once __DIR__ . '/include/retailcrm/init.php';

function pr($item, $show_for = false) {
         global $USER;
         if ($USER->IsAdmin() || $show_for == 'all') {	//intval($show_for)
             if (!$item) echo '<br />пусто<br />';
             elseif (is_array($item) && empty($item)) echo '<br />массив пуст<br />';
             else echo '<pre>' . print_r($item, true) . '</pre>';
         }
}



AddEventHandler("search", "BeforeIndex", Array("MyClass", "BeforeIndexHandler"));
class MyClass{
    // создаем обработчик события "BeforeIndex"
    function BeforeIndexHandler($arFields){
	    if($arFields["MODULE_ID"] == "iblock" && in_array($arFields["PARAM2"],array(2)) && $arFields['ITEM_ID'] && CModule::IncludeModule("iblock")){
	        $srtingProperty = '';

	        $db_props = CIBlockElement::GetProperty($arFields["PARAM2"], $arFields['ITEM_ID'], array("sort" => "asc"), Array("CODE"=>"CHARACTERS"));
			while ($ob = $db_props->GetNext()){
			   $srtingProperty.= $ob['VALUE'].":".$ob['DESCRIPTION'].' ';
			}

			$db_props = CIBlockElement::GetProperty($arFields["PARAM2"], $arFields['ITEM_ID'], array("sort" => "asc"), Array("CODE"=>"TYPE"));
			while ($ob = $db_props->GetNext()){
			    $srtingProperty.= $ob["NAME"].":".$ob['VALUE_ENUM'].' ';
			}

			$db_props = CIBlockElement::GetProperty($arFields["PARAM2"], $arFields['ITEM_ID'], array("sort" => "asc"), Array("CODE"=>"VYAZCOST"));
			while ($ob = $db_props->GetNext()){
			    $srtingProperty.= $ob["NAME"].":".$ob['VALUE_ENUM'].' ';
			}

			$db_props = CIBlockElement::GetProperty($arFields["PARAM2"], $arFields['ITEM_ID'], array("sort" => "asc"), Array("CODE"=>"TYPE_MOTOR"));
			while ($ob = $db_props->GetNext()){
			    $srtingProperty.= $ob["NAME"].":".$ob['VALUE_ENUM'].' ';
			}

			$db_props = CIBlockElement::GetProperty($arFields["PARAM2"], $arFields['ITEM_ID'], array("sort" => "asc"), Array("CODE"=>"SPEC_LIST"));
			while ($ob = $db_props->GetNext()){
			   $srtingProperty.= $ob['VALUE'].":".$ob['DESCRIPTION'].' ';
			}

			$db_props = CIBlockElement::GetProperty($arFields["PARAM2"], $arFields['ITEM_ID'], array("sort" => "asc"), Array("CODE"=>"OPTIONS_TEXT"));
			if($ar_props = $db_props->Fetch()):
				$srtingProperty.= $ob['VALUE']['TEXT'].' ';
			endif;

	        $db_props = CIBlockElement::GetProperty($arFields["PARAM2"], $arFields['ITEM_ID'], array("sort" => "asc"), Array("CODE"=>"MANUFACTURER"));
			if($ar_props = $db_props->Fetch()):
				$srtingProperty.= $ob['VALUE'].' ';
    		endif;

    		$db_props = CIBlockElement::GetProperty($arFields["PARAM2"], $arFields['ITEM_ID'], array("sort" => "asc"), Array("CODE"=>"ARTICLE"));
			if($ar_props = $db_props->Fetch()):
				$srtingProperty.= $ob['VALUE'].' ';
    		endif;



    		$arFields['BODY'] = $arFields['BODY']." ".$srtingProperty;

	    }
	    return $arFields;
    }
}


/**
 * записываем город в новое поле (свойство заказа) SINGLE_LINE_ADDRESS
 * icmark
 */

AddEventHandler("sale", "OnSaleOrderBeforeSaved", "SaveOriginalLocation");

function SaveOriginalLocation($event) {
  if ($_SERVER['REMOTE_ADDR'] == "5.167.139.26") {
    //$event = $values->getParameter("isNew");
    
    $fd = fopen($_SERVER["DOCUMENT_ROOT"]."/test/hello.txt", 'w') or die("не удалось создать файл");
    fwrite($fd, print_r($event, TRUE));
    fclose($fd);
    /*$sityVal = $values->getPropertyCollection()->getItemByOrderPropertyId(24)->getFields()->getValues();
    if ($sityVal["VALUE"] == "-") {
      $orderProp = $values->getPropertyCollection()->getItemByOrderPropertyId(6)->getFields()->getValues();
      $sityCode = Bitrix\Sale\Location\Admin\LocationHelper::getLocationPathDisplay($orderProp['VALUE']);
      $values->getPropertyCollection()->getItemByOrderPropertyId(24)->setValue($sityCode);
    }*/
  }
  
  /*
  if ($arFields['ORDER_PROP']['6']) {
    $arSityCode = $arFields['ORDER_PROP']['6'];
    $arSity = Bitrix\Sale\Location\Admin\LocationHelper::getLocationPathDisplay($arSityCode);
  */  
  
    

    /*
    $thisOrder = CSaleOrderPropsValue::GetOrderProps($ID);
    while ($arPropsThisOrder = $thisOrder->Fetch()) {
      if ($arPropsThisOrder["ORDER_PROPS_ID"] == "24") {
        $thisOrderPropsID = $arPropsThisOrder["ID"];
      }
    }
    if($thisOrderPropsID) {
      $arFieldsForThisOrder = array(
        "ORDER_ID" => $ID,
        "VALUE" => $arSity
       );
      CSaleOrderPropsValue::Update($thisOrderPropsID, $arFieldsForThisOrder);
    }
    */
 



    
    /*$thisOrder = Bitrix\Sale\Order::load($ID);
    $propertyCollection = $thisOrder->getPropertyCollection();
    $sityPropValue = $propertyCollection->getItemByOrderPropertyId(24);
    $sityPropValue->setValue($arSity);
    $sityPropValue->save();*/
    
  /* 
  } 
  */
}



AddEventHandler("sale", "OnSalePayOrder", "ChangeStatus");

function ChangeStatus($id,$val) {
  CSaleOrder::StatusOrder($id,'OO');
}



/**
 * отправка заказа в эватоп после его оплаты картой
 */

AddEventHandler("sale", "OnSaleOrderPaid", "SendOrderToTheEquator");
function SendOrderToTheEquator () {
  CModule::IncludeModule("pavelbabich.kassa");
  PKASSAModuleMain::SendPaidOrdersToEvotor();
}






/*
AddEventHandler("main", "OnAdminTabControlBegin", "MyOnAdminTabControlBegin");
	function MyOnAdminTabControlBegin(&$form)
	{
		if($GLOBALS["APPLICATION"]->GetCurPage() == '/bitrix/admin/sale_order_view.php')
		{         
			$orderID = $_GET['ID'];
			if(intval($orderID) > 0 AND CModule::IncludeModule('sale'))
			{
				$arOrder = CSaleOrder::GetByID($orderID);
				$userID = $arOrder['USER_ID'];
				
				if(intval($userID) > 0)
				{
					$arUser = CUser::GetByID($userID)->Fetch();
					$adminNotes = $arUser["ADMIN_NOTES"];
				   
					$form->tabs[] = array("DIV" => "my_edit", "TAB" => "Дополнительно", "ICON"=>"main_user_edit", "TITLE"=>"Дополнительные поля пользователя", "CONTENT"=>
					'<tr valign="left">
						<td style="width: 50%">VIN авто :</td>
						<td valign="left">'.$arUser['UF_DOP_VIN'].'</td>
					</tr>
					<tr valign="left">
						<td>Марка авто:</td>
						<td valign="left">'.$arUser['UF_DOP_BRAND'].'</td>
					</tr>
					<tr valign="left">
						<td>Модель авто:</td>
						<td valign="left">'.$arUser['UF_DOP_MODEL'].'</td>
					</tr>
					<tr valign="left">
						<td>Год выпуска авто:</td>
						<td valign="left">'.$arUser['UF_DOP_YEAR'].'</td>
					</tr>
					<tr valign="left">
						<td>Пробег авто:</td>
						<td valign="left">'.$arUser['UF_DOP_MILEAGE'].'</td>
					</tr>');
				}
			}  
		}
	}
*/




//AddEventHandler("sale", "OnOrderUpdate", "Test_f");
function Test_f(){
  $fd = fopen($_SERVER["DOCUMENT_ROOT"]."/test/hello.txt", 'w') or die("не удалось создать файл");
  $str = "Привет мир!";
  fwrite($fd, $str);
  fclose($fd);
}


//редактирвоание доп. полей пользователя
AddEventHandler("main", "OnAdminTabControlBegin", "MyOnAdminTabControlBegin");
  function MyOnAdminTabControlBegin(&$form)
  {
    if($GLOBALS["APPLICATION"]->GetCurPage() == '/bitrix/admin/sale_order_view.php')
    {         
      $orderID = $_GET['ID'];
      if(intval($orderID) > 0 AND CModule::IncludeModule('sale'))
      {
        $arOrder = CSaleOrder::GetByID($orderID);
        $userID = $arOrder['USER_ID'];

        if(intval($userID) > 0)
        {
          ob_start();
          CJSCore::Init(array("jquery"));
        ?>
        <script>
          $(function() {
              $('.adm-btn-uf-save').click(function() {
                  $.ajax ({     
                      type: "POST",
                      url: '/ajax/user_uf_edit.php',
                      data: {
                        UF_DOP_VIN:$('input[name="UF_DOP_VIN"]').val(),
                        UF_DOP_BRAND:$('input[name="UF_DOP_BRAND"]').val(),
                        UF_DOP_MODEL:$('input[name="UF_DOP_MODEL"]').val(),
                        UF_DOP_YEAR:$('input[name="UF_DOP_YEAR"]').val(),
                        UF_DOP_MILEAGE:$('input[name="UF_DOP_MILEAGE"]').val(),
                        USER_ID:$('input[name="USER_ID"]').val()
                      },
                      cache: false,
                      success: function(data) {
                        $('tr.message td:nth-child(2)').html(data);
                        setTimeout(
                          function() {
                            $('tr.message td:nth-child(2)').html("");
                          },3000
                        )
                      }
                  });
              }); 
          });
        </script>
        <?
          $sContent = ob_get_clean();
          $GLOBALS['APPLICATION']->AddHeadString($sContent);

          $arUser = CUser::GetByID($userID)->Fetch();
          $adminNotes = $arUser["ADMIN_NOTES"];

          $form->tabs[] = array("DIV" => "my_edit", "TAB" => "Дополнительно", "ICON"=>"main_user_edit", "TITLE"=>"Дополнительные поля пользователя", "CONTENT"=>
            '<tr valign="left">
            <td width="30%">VIN авто :</td>
            <td width="50%" valign="left"><input type="text" name="UF_DOP_VIN" value="'.$arUser['UF_DOP_VIN'].'" /></td>
            </tr>
            <tr valign="left">
            <td>Марка авто:</td>
            <td valign="left"><input type="text" name="UF_DOP_BRAND" value="'.$arUser['UF_DOP_BRAND'].'" /></td>
            </tr>
            <tr valign="left">
            <td>Модель авто:</td>
            <td valign="left"><input type="text" name="UF_DOP_MODEL" value="'.$arUser['UF_DOP_MODEL'].'" /></td>
            </tr>
            <tr valign="left">
            <td>Год выпуска авто:</td>
            <td valign="left"><input type="text" name="UF_DOP_YEAR" value="'.$arUser['UF_DOP_YEAR'].'" /></td>
            </tr>
            <tr valign="left">
            <td>Пробег авто:</td>
            <td valign="left"><input type="text" name="UF_DOP_MILEAGE" value="'.$arUser['UF_DOP_MILEAGE'].'" /></td>
            </tr>
            <tr valign="left">
            <td><input name="USER_ID" value="'.$userID.'" type="hidden" /></td>
            <td valign="left"><input value="Сохранить" title="Сохранить" class="adm-btn-save adm-btn-uf-save" type="submit"></td>
            </tr>
            <tr valign="left" class="message">
            <td height="30px"></td>
            <td valign="left"></td>
            </tr>
          ');
        }
      }  
    }
  }	
  
//вывод доп. полей пользователя  на странице просмотра заказа
\Bitrix\Main\EventManager::getInstance()->addEventHandler("main", "OnAdminSaleOrderViewDraggable", array("UFFileldAdd", "onInit"));

class UFFileldAdd
{
    public static function onInit()
        {
            return array("BLOCKSET" => "UFFileldAdd",
                "getScripts"  => array("UFFileldAdd", "mygetScripts"),
                "getBlocksBrief" => array("UFFileldAdd", "mygetBlocksBrief"),
                "getBlockContent" => array("UFFileldAdd", "mygetBlockContent"),
                );
        }
        
    public static function mygetBlocksBrief($args)
        {
            $id = !empty($args['ORDER']) ? $args['ORDER']->getId() : 0;
            return array(
                'custom1' => array("TITLE" => "Доп. информация пользователя"),
                );
        }
    
    public static function mygetScripts($args)
        {
            return '<script type="text/javascript">... </script>';
        }
        
    public static function mygetBlockContent($blockCode, $selectedTab, $args)
        {
        $result = '';
        $id = !empty($args['ORDER']) ? $args['ORDER']->getId() : 0;
		$arOrder = CSaleOrder::GetByID($id);
		$userID = $arOrder['USER_ID'];
        $arUser = CUser::GetByID($userID)->Fetch();
        if ($selectedTab == 'tab_order')
            {
            if ($blockCode == 'custom1')
                $result = '<table><tr valign="left">
				<td width="30%">VIN авто :</td>
				<td width="50%" valign="left">'.$arUser['UF_DOP_VIN'].'</td>
				</tr>
				<tr valign="left">
				<td>Марка авто:</td>
				<td valign="left">'.$arUser['UF_DOP_BRAND'].'</td>
				</tr>
				<tr valign="left">
				<td>Модель авто:</td>
				<td valign="left">'.$arUser['UF_DOP_MODEL'].'</td>
				</tr>
				<tr valign="left">
				<td>Год выпуска авто:</td>
				<td valign="left">'.$arUser['UF_DOP_YEAR'].'</td>
				</tr>
				<tr valign="left">
				<td>Пробег авто:</td>
				<td valign="left">'.$arUser['UF_DOP_MILEAGE'].'</td>
				</tr>
				</table>';            
        return $result;
        }
} 
}  

	
	
?>