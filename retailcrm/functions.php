<?php
    if (!defined("RX")) die();
    
    /**
    *   @param ApiClient $client = new \RetailCrm\ApiClient(
                URL_RETAILCRM,
                KEYAPI_RETAILCRM
            );
    *   @return array $customers
    */
    function loadCustomersFromRetailcrm($client, $filter = []) {
        $page = 1;
        $limit = 100;
        
        $result = array();
        
        while ($page == 1 or count($res->customers) > 0) {
            $res = $client->customersList($filter, $page, $limit);
            
            
            if (!$res->isSuccessful()) {
                var_dump($res);
                break;
            }
            
            foreach ($res->customers as $customer) {
                // $result[$customer['id']] = array(
                    // 'email' => $customer['email'],
                    // 'phones' => $customer['phones'],
                    // 'id' => $customer['id'],
                // );
                if ($filter['email'] && $filter['email'] == $customer['email']) {
                    $result[$customer['id']] = $customer;
                } else if ($filter['name']) {
                    $result[$customer['id']] = $customer;
                }
            }
            
            $page += 1;
            // break;
        }
        
        return $result;
    }
    
    function formatPhone($phone = '', $trim = true)
    {
        // If we have not entered a phone number just return empty
        if (empty($phone)) {
            return '';
        }
     
        // Strip out any extra characters that we do not need only keep letters and numbers
        $phone = preg_replace("/[^0-9A-Za-z]/", "", $phone);
     
            
        // If we have a number longer than 11 digits cut the string down to only 11
        // This is also only ran if we want to limit only to 11 characters
        if ($trim == true && strlen($phone)>11) {
            $phone = substr($phone,  0, 11);
        } 
     
        // Perform phone number formatting here
        if (strlen($phone) == 7) {
            return preg_replace("/([0-9a-zA-Z]{3})([0-9a-zA-Z]{4})/", "$1-$2", $phone);
        } elseif (strlen($phone) == 10) {
            return preg_replace("/([0-9a-zA-Z]{3})([0-9a-zA-Z]{3})([0-9a-zA-Z]{2})([0-9a-zA-Z]{2})/", "+7 $1 $2-$3-$4", $phone);
        } elseif (strlen($phone) == 11) {
            return preg_replace("/([0-9a-zA-Z]{1})([0-9a-zA-Z]{3})([0-9a-zA-Z]{3})([0-9a-zA-Z]{2})([0-9a-zA-Z]{2})/", "+7 $2 $3-$4-$5", $phone);
        }
     
        // Return original phone if not 7, 10 or 11 digits long
        return $phone;
    }
    
    function getEmailDuplicates($customers) {
        $emailDuplicates = array();
        foreach ($customers as $customer) {
            $email = $customer['email'];
            if ($email) {
                $emailDuplicates[$email][] = $customer;
            }
        }
        foreach ($emailDuplicates as $email => $customers) {
            if (count($customers) <= 1) {
                unset ($emailDuplicates[$email]);
            }
        }
        
        return $emailDuplicates;
    }
    
    function getPhoneDuplicates($customers) {
        $phoneDuplicates = array();
        foreach ($customers as $customer) {
            $phones = $customer['phones'];
            foreach ($phones as $phone) {
                $phoneNumber = formatPhone($phone['number']);
                if ($phoneNumber) {
                    $phoneDuplicates[$phoneNumber][] = $customer;
                }
            }
        }
        foreach ($phoneDuplicates as $phone => $customers) {
            if (count($customers) <= 1) {
                unset ($phoneDuplicates[$phone]);
            }
        }
        
        return $phoneDuplicates;
    }