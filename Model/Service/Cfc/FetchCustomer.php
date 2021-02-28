<?php

namespace Carbonclick\CFC\Model\Service\Cfc;


class FetchCustomer extends CreateShop
{

    public function getCustomerInfo(){
        $customerInfo = $this->SendFetchCustomerRequest();
        if($customerInfo){
            return $this->jsonHelper->jsonDecode($customerInfo);
        }
        return;
    }

    private function SendFetchCustomerRequest(){
    	$shop = $this->getShop();
        if(empty($shop)){
            return;
        }
    	try{
            $this->curl->setOption(CURLOPT_TIMEOUT, 60);
            $this->curl->addHeader("Content-Type", "application/json");
            $this->curl->addHeader("Accept", "application/json");
            $this->curl->addHeader("Authorization", "Bearer ".$shop['access_token']);
            $this->curl->get(self::CARBONCLICK_CONFIG_URL.'api/shops/details');
            $response = $this->curl->getBody();
            if($this->curl->getStatus() == 200){
                return $response;
            }else{
                throw new \Exception($response);   
            }
        }catch(\Exception $e){
            $this->logger->error($e->getMessage());	
            return;
        }
        return;
    }
}
