<?php
namespace Sale\Handlers\PaySystem;

use Bitrix\Main\Error;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Request;
use Bitrix\Main\Type\DateTime;
use Bitrix\Sale\PaySystem;
use Bitrix\Sale\Payment;
use Bitrix\Main\Config\Option;

Loc::loadMessages(__FILE__);

class raiffeisenbankHandler extends PaySystem\ServiceHandler implements PaySystem\IRefund
{
    protected function getUrlList()
    {
        return array(
            'pay' => array(
                self::TEST_URL => "https://test.ecom.raiffeisen.ru/vsmc3ds/pay_check/3dsproxy_init.jsp",
                self::ACTIVE_URL => "https://e-commerce.raiffeisen.ru/vsmc3ds/pay_check/3dsproxy_init.jsp",
            ),
            'refund' => array(
                self::TEST_URL => "https://e-commerce.raiffeisen.ru/portal_test/xml_request.jsp",
                self::ACTIVE_URL => "https://e-commerce.raiffeisen.ru/portal/xmppinit.jsp",
            )
        );
    }

    public function getPaymentIdFromRequest(Request $request)
    {
        $dbPayment = \Bitrix\Sale\Internals\PaymentTable::getList(array(
            "filter" => array("ORDER_ID" => $this->getOrderID($request['descr'])),
            "select" => array("ID"),
        ));

        if ($arPayment = $dbPayment->fetch()) {
            return $arPayment["ID"];
        }
        return false;
    }

    private function getOrderID($number)
    {
        $dbOrder = \Bitrix\Sale\Internals\OrderTable::getList(array(
            "filter" => array("ACCOUNT_NUMBER" => $number),
            "select" => array("ID"),
        ));

        if ($arOrder = $dbOrder->fetch()) {
            $result = $arOrder["ID"];
        }
        return $result;
    }

    static protected function isMyResponseExtended(Request $request, $paySystemId)
    {
        return $request->get('CZ_HANDLER_ID') == $paySystemId;
    }

    public static function getIndicativeFields()
    {
        return array('CZ_HANDLER_NAME' => "RAIFFEISENBANKHANDLER");
    }

    protected function isTestMode()
    {
        return ($this->getBusinessValue(null, 'ShopIsTest') == 'Y');
    }

    public function getCurrencyList()
    {
        return array('RUB');
    }

    public function initiatePay(Payment $payment, Request $request = null)
    {
        $params = $this->getParamsBusValue($payment);
        $url = $this->getUrl($payment, 'pay');
        $arError = $this->validSetting($params);
        if (count($arError) == 0) {
            $settings = array(
                "PurchaseAmt" => $this->getSum($params["PaymentSum"]),
                "PurchaseDesc" => $this->service->getField('ID')."_".$params["OrderNum"],
                "MerchantName" => $params["MerchantName"],
                "MerchantID" => "00000".$params["MerchantID"]."-".substr($params["MerchantID"], 2),
                "MerchantURL" => $params["MerchantURL"],
                "MerchantCity " => $params["MerchantCity"],
                "SuccessURL" => $params["SuccessURL"],
                "FailURL" => $params["FailURL"],
                "Language" => $params["Language"],
                "Mobile" => $params["Mobile"],
                "HMAC" => $this->getHash($params),

                "URL_INIT" => $url,
                "NEW_WINDOW" => $this->service->getField('NEW_WINDOW'),
                "ATOL" => $params["Atol"],
            );
            if ($params["Atol"] == "Y") {
                $this->getAtolInfo($params, $settings);
            }
        } else {
            $settings = array(
                "ERROR" => $arError,
            );
        }
        $this->setExtraParams($settings);
        return $this->showTemplate($payment, 'template');
    }

    protected function validSetting($params)
    {
        $result = array();
        if (strlen($params['MerchantID']) != 10  || !is_numeric($params['MerchantID']))  {
            $result[] = Loc::getMessage("CZ_RB_VALID_MerchantID");
        }
        if (strlen($params['MerchantName']) == 0) {
            $result[] = Loc::getMessage("CZ_RB_VALID_MerchantName");
        }
        if (!is_numeric($params['PaymentSum'])) {
            $result[] = Loc::getMessage("CZ_RB_VALID_PaymentSum");
        }
        if (strlen($params['OrderNum']) == 0) {
            $result[] = Loc::getMessage("CZ_RB_VALID_OrderNum");
        }
        if (strlen($params['MerchantURL']) == 0) {
            $result[] = Loc::getMessage("CZ_RB_VALID_MerchantURL");
        }
        if (strlen($params['MerchantCity']) == 0) {
            $result[] = Loc::getMessage("CZ_RB_VALID_MerchantCity");
        }
        if (strlen($params['SuccessURL']) == 0) {
            $result[] = Loc::getMessage("CZ_RB_VALID_SuccessURL");
        }
        if (strlen($params['FailURL']) == 0) {
            $result[] = Loc::getMessage("CZ_RB_VALID_FailURL");
        }
        return $result;
    }

    protected function getSum($sum)
    {
        $result = "";
        $sum = explode(".", $sum);
        if (strlen($sum[1]) > 2) {
            $result = $sum[0].".".substr($sum[1], 0, 2);
        } elseif (strlen($sum[1]) == 2) {
            $result = $sum[0] . "." . $sum[1];
        } elseif (strlen($sum[1]) == 1) {
            $result = $sum[0].".".$sum[1]."0";
        } else {
            $result = $sum[0].".00";
        }
        return $result;
    }

    protected function getHash($params)
    {
        $str = "";
        $key = ($this->isTestMode())
            ? Option::get("czebra.raiffeisenbank", "SECRET_KEY_TEST", "")
            : Option::get("czebra.raiffeisenbank", "SECRET_KEY", "");
        if (strlen($key) > 0) {
            $str = $params["MerchantID"].";".substr($params["MerchantID"], 2).";".$this->service->getField('ID')."_".$params["OrderNum"].";".$this->getSum($params["PaymentSum"]);
            $str = base64_encode(pack('H*',hash_hmac('sha256', $str, base64_decode($key), false)));
        }
        return $str;
    }

    protected function getAtolInfo($params, &$settings)
    {
        $ext1 = "";
        $ext1 .= "external_id:" . $this->service->getField('ID') . "_" . $params["OrderNum"] . ",";
        $ext1 .= "total:" . $this->getSum($params["PaymentSum"]) . ",";
        $ext1 .= "email:" . $params["UserEmail"] . ",";
        $ext1 .= "phone:" . $this->getPhoneAtol($params["UserPhone"]) . ",";
        $ext1 .= "sno:" . $params["Sno"] . ",";
        $ext1 .= "callback_url:" . $params["AtolCallbackUrl"] . ";";
        $ext1 .= "payments_sum:" . $this->getSum($params["PaymentSum"]) . ",";
        $ext1 .= "payments_type:1";

        $orderID = $this->getOrderID($params["OrderNum"]);
        $dbOrder = \CSaleBasket::GetList(array(), array('ORDER_ID' => $orderID));
        $i = 0;
        $ext2 = "";
        while ($arOrder = $dbOrder->Fetch()) {
            $ext2 .= ($i > 0) ? ";" : "";
            $ext2 .= "sum:" . $this->getSum($arOrder['PRICE'] * $arOrder['QUANTITY']) . ",";
            $ext2 .= "tax:" . $this->getVatAtol($arOrder) . ",";
            $ext2 .= "tax_sum:" . $this->getSum($arOrder['VAT_RATE']) . ",";
            $ext2 .= "name:" . $this->getNameAtol($arOrder['NAME']) . ",";
            $ext2 .= "price:" . $this->getSum($arOrder['PRICE']) . ",";
            $ext2 .= "quantity:" . $arOrder['QUANTITY'];
            $i++;
        }

        $settings["Ext1"] = substr(json_encode($ext1), 1, -1);
        $settings["Ext2"] = $ext2;
    }

    public function processRequest(Payment $payment, Request $request)
    {
        $result = new PaySystem\ServiceResult();
		$type = $request->get('type');
        $params = $this->getParamsBusValue($payment);
		if (strlen($request["hmac"]) == 0 || $request["hmac"] == $this->getHash($params)) {
			if ($type == 'conf_pay') {
				return $this->processNoticeAction($payment, $request);
			} elseif ($type == 'conf_reversal') {
				return $this->processCancelAction($payment, $request);
			} else {
				$data = $this->extractDataFromRequest($request);
				$data['TECH_MESSAGE'] = 'Unknown action: '.$action;
				$result->setData($data);
				$result->addError(new Error('Unknown action: '.$action.'. Request='.join(', ', $request->toArray())));
			}
		} else {
			$data = $this->extractDataFromRequest($request);
			$data['TECH_MESSAGE'] = 'Incorrect hash sum';
            $data['CODE'] = 1;

			$result->setData($data);
			$result->addError(new Error('Incorrect hash sum'));
		}

		if (!$result->isSuccess()) {
			PaySystem\ErrorLog::add(array(
				'ACTION' => 'processRequest: '.$action,
				'MESSAGE' => join('\n', $result->getErrorMessages())
			));

		}

        return $result;
    }

    private function processNoticeAction(Payment $payment, Request $request)
    {
        $result = new PaySystem\ServiceResult();
        $fields = array(
            "PS_STATUS" => "Y",
			"PS_STATUS_CODE" => $request["id"],
			"PS_STATUS_DESCRIPTION" => "ID: ".$request["id"]." comment: " . $request["comment"],
			"PS_STATUS_MESSAGE" => '',
			"PS_SUM" => $request->get('amt'),
            "PS_CURRENCY" => "RUB",
			"PS_RESPONSE_DATE" => new DateTime(),
			"PS_INVOICE_ID" => $request->get('id')
        );

		if ($this->isCorrectSum($payment, $request) && $request["result"] == "0") {
			$data['CODE'] = 0;
			$fields["PS_STATUS"] = "Y";
			//if ($this->getBusinessValue($payment, 'PS_CHANGE_STATUS_PAY') == 'Y'){
                $result->setOperationType(PaySystem\ServiceResult::MONEY_COMING);
                echo "RESP_CODE=0";//for callback
            //}
		} else {
			$data['CODE'] = 200;
			$fields["PS_STATUS"] = "N";
			$errorMessage = 'Incorrect payment sum';

			$result->addError(new Error($errorMessage));
			PaySystem\ErrorLog::add(array(
				'ACTION' => 'paymentAvisoResponse',
				'MESSAGE' => $errorMessage
			));
		}

		$result->setData($data);
		$result->setPsData($fields);

		return $result;
    }

    private function processCancelAction(Payment $payment, Request $request)
	{
		$result = new PaySystem\ServiceResult();
        $params = $this->getParamsBusValue($payment);

        if ($request["result"] == 0/* || $request["result"] == 1*/) {
            $data['CODE'] = 0;
            $result->setOperationType(PaySystem\ServiceResult::MONEY_LEAVING);
            echo "RESP_CODE=0";//for callback
        }

		$result->setData($data);

		return $result;
	}

    private function isCorrectSum(Payment $payment, Request $request)
	{
		$sum = $request->get('amt');
		$paymentSum = $payment->getField('SUM');
        return $this->roundByFormatCurrency($paymentSum, $payment->getField('CURRENCY')) == $this->roundByFormatCurrency($sum, $payment->getField('CURRENCY'));
    }

    private function roundByFormatCurrency($price, $currency)
    {
        return floatval(SaleFormatCurrency($price, $currency, false, true));
    }

    private function getPhoneAtol($phone)
    {
        $phone = preg_replace('~[^0-9]+~','',$phone);
        if (strlen($phone) > 0) {
            $phone = ($phone[0] == "7") ? substr($phone, 1) : $phone;
            $phone = ($phone[0] == "8") ? substr($phone, 1) : $phone;
        }
        return $phone;
    }

    private function getNameAtol($name)
    {
        $name = str_replace(',', '', $name);
        $name = str_replace(';', '', $name);
        $name = str_replace('=', '', $name);
        if (LANG_CHARSET != "UTF-8") {
            $name = iconv(LANG_CHARSET, "UTF-8", $name);
        }
        return $name;
    }

    private function getVatAtol($arOrder)
    {
        return $arOrder['VAT_INCLUDED'] == 'Y' ? (in_array(intval($arOrder['NAME']), array(0, 10, 18)) ? 'vat' . intval($arOrder['NAME']) : 0) : 'none';
    }

    public function refund(Payment $payment, $refundableSum)
	{
        $result = new PaySystem\ServiceResult();
        $params = $this->getParamsBusValue($payment);
        $arParam = array(
            "Package" => "merchant",
            "Function" => "reversal",
            "UserName" => $params["Login"],
            "UserPassword" => $params["Password"],
            "MerchantID" => $params["MerchantID"],
            "PurchaseDesc" => $this->service->getField('ID')."_".$params["OrderNum"]
        );
        $url = $this->getUrl($payment, 'refund');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, "Opera/10.00 (Windows NT 5.1; U; ru) Presto/2.2.0");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/x-www-form-urlencoded'));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($arParam));
        $response = curl_exec($curl);
        curl_close($curl);

        if ($response !== false) {
            $xml = simplexml_load_string((string)$response);
            if ($xml->status == 'ok') {
                //$result->setOperationType(PaySystem\ServiceResult::MONEY_LEAVING);
            } else {
                $error .= Loc::getMessage("CZ_RB_ERR_1").(string)$response;
            }
        } else {
            $error .= Loc::getMessage("CZ_RB_ERR_2");
        }

		if ($error !== '')
		{
			$result->addError(new Error($error));
			PaySystem\ErrorLog::add(array(
				'ACTION' => 'returnPaymentRequest',
				'MESSAGE' => join("\n", $result->getErrorMessages())
			));
		}

		return $result;
    }
}
