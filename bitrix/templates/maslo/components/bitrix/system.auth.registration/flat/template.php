<?$APPLICATION->IncludeComponent( 
   "bitrix:main.register", 
   "", 
   Array( 
      "USER_PROPERTY_NAME" => "Дополнительная информация", // название блока с пользовательскими полями 
      "SEF_MODE" => "N", 
      "SHOW_FIELDS" => Array("NAME","LAST_NAME","PERSONAL_PHONE"), // стандартные поля 
      "REQUIRED_FIELDS" => Array("NAME","LAST_NAME","PERSONAL_PHONE"), // обязательные 
      "AUTH" => "Y", 
      "USE_BACKURL" => "Y", 
      "SUCCESS_PAGE" => $APPLICATION->GetCurPageParam('',array('backurl')), 
      "SET_TITLE" => "N", 
      "USER_PROPERTY" => Array(), // пользовательские поля 
   ) 
);?>
