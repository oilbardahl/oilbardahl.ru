<?php	
    define('RX', true);
    define('RETAILCRM_SITE', 'oilbardahl');
    
    $hash = $_REQUEST['hash'];
    if ($hash != 'DAWDA3214FDs33')
        die();
        
    require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include.php';
    require_once 'functions.php';
    
    function phone_in_array($phone, $phoneArray) {
        foreach ($phoneArray as $ph) {
            if (formatPhone($phone['number']) == formatPhone($ph['number']))
                return true;
        }
        return false;
    }
       
    if (!CModule::IncludeModule("intaro.retailcrm")) die();
	

	$api_by = 'id';
    $api_host = COption::GetOptionString("intaro.retailcrm", "api_host");
    $api_key = COption::GetOptionString("intaro.retailcrm", "api_key");
    $client = new \RetailCrm\ApiClient($api_host, $api_key);
            
    $actions = ['phone', 'email'];
    
    $customerId = $_REQUEST['customer_id'];
    $response = $client->customersGet($customerId, 'id');
    $customer = $response->customer;
    
    $site = $customer['site'];
    
    
    $userEmail = $customer['email'];
    $phones = $customer['phones'];
	    
    foreach ($actions as $action)
    {
        switch ($action) {
            case 'email':
                if (!$userEmail)
                    break;
                
                $filter = ['email' => $userEmail];
                $emailDuplicates[$userEmail] = loadCustomersFromRetailcrm($client, $filter);
				                
                foreach ($emailDuplicates as $email => $customers) {
                    if ($email == 'spam@oilbardahl.ru')
                        continue;
                    //   ID
                    uasort($customers, function($customer1, $customer2) {
                        if ($customer1['externalId'] && $customer2['externalId']) {
                            return $customer1['id'] < $customer2['id'];
                        } else {
                            if ($customer1['externalId'])
                                return false;
                            if ($customer2['externalId'])
                                return true;
                        }
                        return $customer1['id'] < $customer2['id'];
                    });
					
					// 
                    $resultCustomer = array_shift($customers);
                    
                    //      
                    foreach ($customers as $customer) {
                        foreach ($customer as $key => $value) {
                            switch ($key) {
                                case 'phones':
                                    foreach ($value as $phone) {
                                        if (!phone_in_array($phone, $resultCustomer['phones'])) {
                                            if ($phone['number'] && count($resultCustomer['phones']) < 4)
                                                $resultCustomer['phones'][] = $phone;
                                        }
                                    }
                                    break;
                                    
                                case 'address':
                                case 'customFields':
                                    foreach ($customer[$key] as $key2 => $value2) {
                                        if (!$resultCustomer[$key][$key2]) {
                                            $resultCustomer[$key][$key2] = $value2;
                                        }
                                    }
                                    break;
                                    
                                default:
                                    if (!$resultCustomer[$key]) {
                                        $resultCustomer[$key] = $value;
                                    }
                            }
                        }
                    }
                    
                    if (!$resultCustomer['site'])
                            $resultCustomer['site'] = RETAILCRM_SITE;
                    
                    unset($resultCustomer['managerId']);
                    unset($resultCustomer['externalId']);
                    // unset($resultCustomer['site']);
                    // $res = $client->customersEdit($resultCustomer, 'id', $site);
                    $res = $client->customersEdit($resultCustomer, 'id', $resultCustomer['site']);
                    
                    if (!$res->isSuccessful()) {
                        var_dump($resultCustomer);
                        var_dump($res);
                        die();
                    }
                    
                    //  
                    $customers_array = array_chunk($customers, 50);
                    foreach ($customers_array as $customers) {
                        try {
                            $res = $client->customersCombine($customers, $resultCustomer);
                        } catch (Exception $e) {
                            echo $e->getMessage();
                        }
                        
                        if (!$res->isSuccessful()) {
                            var_dump($res);
                            die();
                        }
                    }
                    
                }
                echo "Success";
                break;
                
            case 'phone':
                if (count($phones) > 4)
                    break;
                
                if (count($phones) == 0)
                    break;
                
                foreach ($phones as $phonenumber)
                {
                    $userPhone = $phonenumber['number'];
                    
                    $phonecheck = preg_replace('/\D/', '', $userPhone);
                    if (strlen($phonecheck) != 11)
                        continue;
                                        
                    $filter = ['name' => $userPhone];
                    $phoneDuplicates[$userPhone] = loadCustomersFromRetailcrm($client, $filter);
                    					                                        
                    if (count($phoneDuplicates[$userPhone]) <= 1)
                        continue;
                    
                    // if (count($phoneDuplicates[$userPhone]) > 3) {
                        // $message = '    .  ' . $phonenumber;
                        
                        // $email = 'e7a5f7t8f4l5k2f5@analitika-online.slack.com';
                        // $subject = "[bohemianflowers.ru]   ";
                        // $body = "[bohemianflowers.ru] " . $message;
                        // $headers = "Content-Type: text/html; charset=UTF-8";
                        // mail( $email, $subject, $body, $headers);
                        // continue;
                    // }
					
                    foreach ($phoneDuplicates as $phone => $customers) {
                        if (count($customers) <= 1)
                            continue;

                        $ids = [];
                        $newCustomers = [];
                        foreach ($customers as $customer) {
                            if (in_array($customer['id'], $ids)) {
                                // unset($customer[$i]);
                            } else {
                                $ids[] = $customer['id'];
                                $newCustomers[] = $customer;
                            }
                        }
                        $customers = $newCustomers;

                        //   ID
                        uasort($customers, function($customer1, $customer2) {
                            if ($customer1['externalId'] && $customer2['externalId']) {
                                return $customer1['id'] > $customer2['id'];
                            } else {
                                if ($customer1['externalId'])
                                    return false;
                                if ($customer2['externalId'])
                                    return true;
                            }
                            return $customer1['id'] > $customer2['id'];
                        });
                        

                        // 
                        $resultCustomer = array_shift($customers);
                        $phones = [];
                        foreach ($resultCustomer['phones'] as $phone) {
                            if (!in_array(formatPhone($phone['number']), $phones)) {
                                if ($phone['number'])
                                    $phones[] = formatPhone($phone['number']);
                            }
                        }
                        // $phones = array_unique($phones);
                        $resultCustomer['phones'] = [];
                        foreach ($phones as $phoneNumber) {
                            if ($phoneNumber)
                                $resultCustomer['phones'][] = ['number' => $phoneNumber];
                        }
                                                
                        //      
                        foreach ($customers as $customer) {
                            foreach ($customer as $key => $value) {
                                switch ($key) {
                                    case 'phones':
                                        $phones = [];
                                        foreach ($value as $phone) {
                                            if (!in_array(formatPhone($phone['number']), $phones)) {
                                                if ($phone['number'])
                                                    $phones[] = formatPhone($phone['number']);
                                            }
                                        }
                                        $phones2 = [];
                                        foreach ($phones as $phoneNumber) {
                                            if ($phoneNumber)
                                                $phones2[] = ['number' => $phoneNumber];
                                        }
                                        $resultCustomer['phones'] = array_unique(array_merge($resultCustomer['phones'], $phones2));
                                        break;
                                        
                                    case 'address':
                                    case 'customFields':
                                        foreach ($customer[$key] as $key2 => $value2) {
                                            if ($value2) {
                                                if (!$resultCustomer[$key][$key2]) {
                                                    $resultCustomer[$key][$key2] = $value2;
                                                } else {
                                                    if ($key2 == 'additional_emails') {
                                                        $resultCustomer[$key][$key2] .= ', ' . $value2;
                                                    }
                                                }
                                            }
                                        }
                                        break;
                                        
                                    case 'email':
                                        if ($value) {
                                            if (!$resultCustomer[$key]) {
                                                $resultCustomer[$key] = $value;
                                                break;
                                            }
                                            
                                            if ($resultCustomer[$key] == 'spam@oilbardahl.ru') {
                                                $resultCustomer[$key] = $value;
                                                break;
                                            }
                                            
                                            if ($resultCustomer[$key] == $value)
                                                break;
                                            
                                            $additionalEmails = array_map(trim, explode(',', $resultCustomer['customFields']['additional_emails']));
                                            $additionalEmails[] = $value;
                                            $additionalEmails = array_filter(array_unique($additionalEmails));
                                            $resultCustomer['customFields']['additional_emails'] = implode(', ', $additionalEmails);
                                        }
                                        
                                    default:
                                        if (!$resultCustomer[$key]) {
                                            $resultCustomer[$key] = $value;
                                        }
                                }
                            }
                        }
                        
                        if (!$resultCustomer['site'])
                            $resultCustomer['site'] = RETAILCRM_SITE;

                        $resultCustomer = [
                            'id' => $resultCustomer['id'],
                            'email' => $resultCustomer['email'],
                            'phones' => $resultCustomer['phones'],
                            'site' => $resultCustomer['site'],
                        ];
                        
                        // unset($resultCustomer['segments']);
                        // unset($resultCustomer['managerId']);
                        // unset($resultCustomer['externalId']);
                        // unset($resultCustomer['site']);
                        // $res = $client->customersEdit($resultCustomer, 'id', $site);
                        $res = $client->customersEdit($resultCustomer, 'id', $resultCustomer['site']);
                        var_dump($res);

                        if (!$res->isSuccessful()) {
                            echo "<pre>";
                            // var_dump('EDIT CUSTOMER');
                            var_dump($resultCustomer);
                            var_dump($res);
                            die();
                        }
                        
                                        

                        //  
                        $customers_array = array_chunk($customers, 50);
                        foreach ($customers_array as $customers) {
                            try {
                                $res = $client->customersCombine($customers, $resultCustomer);
                            } catch (Exception $e) {
                                echo $e->getMessage();
                                var_dump($customers);
                                var_dump($resultCustomer);
                                die();
                            }

                            if (!$res->isSuccessful()) {
                                if ($res->getStatusCode() != 400) {
                                    var_dump('COMBINE');
                                    var_dump($customers);
                                    var_dump($resultCustomer);
                                    var_dump($res);
                                    die();
                                }
                            }

                        }
                        
                    }
                }
                echo "Success";
                break;
        }
    
    }