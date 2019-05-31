<?php
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$server = \Bitrix\Main\Application::getInstance()->getContext()->getServer();
$url = $request->isHttps() ? "https://" : "http://";
$url .= $server->getHttpHost();

$description = array(
	'MAIN' => Loc::getMessage('CZ_RB_DESCRIPTION_PAYSYSTEM').$url."/bitrix/raiffeisenbank/"
);

$data = array(
	'NAME' => Loc::getMessage('CZ_RB_NAME'),
	'SORT' => 500,
	'CODES' => array(
		//MODE
		'ShopIsTest' => array(
			'NAME' => Loc::getMessage('CZ_RB_ShopIsTest'),
			'DESCRIPTION' => Loc::getMessage('CZ_RB_DESC_ShopIsTest'),
			'SORT' => 100,
			'GROUP' => Loc::getMessage('CZ_RB_TAB_MODE'),
			'INPUT' => array(
				'TYPE' => 'Y/N'
			),
			'DEFAULT' => array(
				"PROVIDER_VALUE" => "Y",
				"PROVIDER_KEY" => "INPUT"
			)
		),
		//CONNECT_SETTINGS
		'MerchantID' => array(
			'NAME' => Loc::getMessage('CZ_RB_MerchantID'),
			'DESCRIPTION' => Loc::getMessage('CZ_RB_DESC_MerchantID'),
			'SORT' => 200,
			'GROUP' => Loc::getMessage('CZ_RB_TAB_CONNECT_SETTINGS'),
		),
		'MerchantName' => array(
			'NAME' => Loc::getMessage('CZ_RB_MerchantName'),
			'DESCRIPTION' => Loc::getMessage('CZ_RB_DESC_MerchantName'),
			'SORT' => 220,
			'GROUP' => Loc::getMessage('CZ_RB_TAB_CONNECT_SETTINGS'),
		),
		'MerchantURL' => array(
			'NAME' => Loc::getMessage('CZ_RB_MerchantURL'),
			'DESCRIPTION' => Loc::getMessage('CZ_RB_DESC_MerchantURL'),
			'SORT' => 225,
			'GROUP' => Loc::getMessage('CZ_RB_TAB_CONNECT_SETTINGS'),
			'DEFAULT' => array(
				'PROVIDER_KEY' => 'VALUE',
				"PROVIDER_VALUE" => $url
			),
		),
		'MerchantCity' => array(
			'NAME' => Loc::getMessage('CZ_RB_MerchantCity'),
			'DESCRIPTION' => Loc::getMessage('CZ_RB_DESC_MerchantCity'),
			'SORT' => 230,
			'GROUP' => Loc::getMessage('CZ_RB_TAB_CONNECT_SETTINGS'),
		),
		//ORDER_INFO
		'OrderNum' => array(
			"NAME" => Loc::getMessage('CZ_RB_OrderNum'),
			'SORT' => 300,
			'GROUP' => Loc::getMessage('CZ_RB_TAB_ORDER_INFO'),
			'DEFAULT' => array(
				'PROVIDER_KEY' => 'ORDER',
				'PROVIDER_VALUE' => 'ACCOUNT_NUMBER'
			)
		),
		'PaymentSum' => array(
			"NAME" => Loc::getMessage('CZ_RB_PaymentSum'),
			'SORT' => 315,
			'GROUP' => Loc::getMessage('CZ_RB_TAB_ORDER_INFO'),
			'DEFAULT' => array(
				'PROVIDER_KEY' => 'PAYMENT',
				'PROVIDER_VALUE' => 'SUM'
			)
		),
		//MORE_PARAM
		'SuccessURL' => array(
			'NAME' => Loc::getMessage('CZ_RB_SuccessURL'),
			'DESCRIPTION' => Loc::getMessage('CZ_RB_DESC_SuccessURL'),
			'SORT' => 400,
			'GROUP' => Loc::getMessage('CZ_RB_TAB_MORE_PARAM'),
			'DEFAULT' => array(
				'PROVIDER_KEY' => 'VALUE',
				"PROVIDER_VALUE" => $url."/bitrix/raiffeisenbank/success.php"
			),
		),
		'FailURL' => array(
			'NAME' => Loc::getMessage('CZ_RB_FailURL'),
			'DESCRIPTION' => Loc::getMessage('CZ_RB_DESC_FailURL'),
			'SORT' => 405,
			'GROUP' => Loc::getMessage('CZ_RB_TAB_MORE_PARAM'),
			'DEFAULT' => array(
				'PROVIDER_KEY' => 'VALUE',
				"PROVIDER_VALUE" => $url."/bitrix/raiffeisenbank/fail.php"
			),
		),
		'Language' => array(
			'NAME' => Loc::getMessage('CZ_RB_Language'),
			'DESCRIPTION' => Loc::getMessage('CZ_RB_DESC_Language'),
			'SORT' => 415,
			'GROUP' => Loc::getMessage('CZ_RB_TAB_MORE_PARAM'),
			"INPUT" => array(
				'TYPE' => 'ENUM',
				'OPTIONS' => array(
					Loc::getMessage('CZ_RB_Language_ru') => 'ru',
					Loc::getMessage('CZ_RB_Language_en') => 'en',
				)
			)
		),
		'Mobile' => array(
			'NAME' => Loc::getMessage('CZ_RB_Mobile'),
			'DESCRIPTION' => Loc::getMessage('CZ_RB_DESC_Mobile'),
			'SORT' => 420,
			'GROUP' => Loc::getMessage('CZ_RB_TAB_MORE_PARAM'),
			'INPUT' => array(
				'TYPE' => 'Y/N'
			)
		),
		//ATOL_PARAMS
		'Atol' => array(
			'NAME' => Loc::getMessage('CZ_RB_Atol'),
			'DESCRIPTION' => Loc::getMessage('CZ_RB_DESC_Atol'),
			'SORT' => 500,
			'GROUP' => Loc::getMessage('CZ_RB_TAB_ATOL_PARAMS'),
			'INPUT' => array(
				'TYPE' => 'Y/N'
			)
		),
		'Sno' => array(
			'NAME' => Loc::getMessage('CZ_RB_Sno'),
			'DESCRIPTION' => Loc::getMessage('CZ_RB_DESC_Sno'),
			'SORT' => 505,
			'GROUP' => Loc::getMessage('CZ_RB_TAB_ATOL_PARAMS'),
			"INPUT" => array(
				'TYPE' => 'ENUM',
				'OPTIONS' => array(
					'osn' => Loc::getMessage('CZ_RB_SNO_OSN'),
					'usn_income' => Loc::getMessage('CZ_RB_SNO_USN_INCOME'),
					'usn_income_outcome' => Loc::getMessage('CZ_RB_SNO_USN_OUTCOME'),
					'envd' => Loc::getMessage('CZ_RB_SNO_ENVD'),
					'esn' => Loc::getMessage('CZ_RB_SNO_ESN'),
					'patent' => Loc::getMessage('CZ_RB_SNO_PATENT')
				)
			)
		),
		'AtolCallbackUrl' => array(
			'NAME' => Loc::getMessage('CZ_RB_AtolCallbackUrl'),
			'DESCRIPTION' => Loc::getMessage('CZ_RB_DESC_AtolCallbackUrl'),
			'SORT' => 510,
			'GROUP' => Loc::getMessage('CZ_RB_TAB_ATOL_PARAMS'),
			'DEFAULT' => array(
				'PROVIDER_KEY' => 'VALUE',
				"PROVIDER_VALUE" => $url
			),
		),
		'UserEmail' => array(
			'NAME' => Loc::getMessage('CZ_RB_UserEmail'),
			'DESCRIPTION' => Loc::getMessage('CZ_RB_DESC_UserEmail'),
			'SORT' => 515,
			'GROUP' => Loc::getMessage('CZ_RB_TAB_ATOL_PARAMS'),
			'DEFAULT' => array(
				'PROVIDER_KEY' => 'PROPERTY'
			)
		),
		'UserPhone' => array(
			'NAME' => Loc::getMessage('CZ_RB_UserPhone'),
			'DESCRIPTION' => Loc::getMessage('CZ_RB_DESC_UserPhone'),
			'SORT' => 520,
			'GROUP' => Loc::getMessage('CZ_RB_TAB_ATOL_PARAMS'),
			'DEFAULT' => array(
				'PROVIDER_KEY' => 'PROPERTY'
			)
		),

		//REFUND
		'Login' => array(
            'NAME' => Loc::getMessage('CZ_RB_Login'),
            'DESCRIPTION' => Loc::getMessage('CZ_RB_DESC_Login'),
            'SORT' => 610,
            'GROUP' => Loc::getMessage('CZ_RB_TAB_REFUND'),
        ),
        'Password' => array(
            'NAME' => Loc::getMessage('CZ_RB_Password'),
            'DESCRIPTION' => Loc::getMessage('CZ_RB_DESC_Password'),
            'SORT' => 615,
            'GROUP' => Loc::getMessage('CZ_RB_TAB_REFUND'),
        ),

	)
);
