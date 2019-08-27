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

use Bitrix\Main;
Main\EventManager::getInstance()->addEventHandler(
  'sale',
  'OnSaleOrderBeforeSaved',
  'SaveOriginalLocation'
);

function SaveOriginalLocation(Main\Event $event) {
    $order = $event->getParameter("ENTITY");
    if ($order) {
        $isNew = $order->isNew();
        if ($isNew == "1")
        {
            $propertyCollection = $order->getPropertyCollection();

            $props = [];
            $orderData = $propertyCollection->getArray();
            foreach ($orderData['properties'] as $prop) {
                $props[$prop['CODE']] = $prop['VALUE'][0];
            }

            var_dump($props);

            $cityCode = $props['LOCATION'];

            $ID = CSaleLocation::getLocationIDbyCODE( $cityCode );
            $arVal = CSaleLocation::GetByID( $ID );

            $fullCityName = $arVal['COUNTRY_NAME'];
            if ($arVal['REGION_NAME'])
                $fullCityName .= ', ' . $arVal['REGION_NAME'];
            if ($arVal['CITY_NAME'])
                $fullCityName .= ', ' . $arVal['CITY_NAME'];

            $fullAddress = $fullCityName;
            if ($props['CLIENT_STREET'])
                $fullAddress .= ', ул.' . trim($props['CLIENT_STREET']);
            if ($props['HOUSE'])
                $fullAddress .= ', д.' . trim($props['HOUSE']);
            if ($props['KORPUS'])
                $fullAddress .= '/' . trim($props['KORPUS']);
            if ($props['KVARTIRA'])
                $fullAddress .= ', кв.' . trim($props['KVARTIRA']);
            if ($props['OFFICE'])
                $fullAddress .= ', оф.' . trim($props['OFFICE']);
            
            $propertyCollection->getItemByOrderPropertyId(24)->setValue($fullAddress);
        }
    }
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