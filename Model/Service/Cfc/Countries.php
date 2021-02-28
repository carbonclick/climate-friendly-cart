<?php

namespace Carbonclick\CFC\Model\Service\Cfc;


class Countries extends Authentication
{

    public function getStoretaxable(){
		$countries = $this->SendCountriesRequest();
		if($countries){
			$data = $this->jsonHelper->jsonDecode($countries);
            $storecountry = $this->getStoreCountry();
            foreach ($data['data'] as $value) {
                if($value["country_alpha2"] == $storecountry){
                    return $value["taxable"];
                    break;
                }
            }
		}
    	return;
    }

    private function SendCountriesRequest(){
    	try{
            $this->curl->setOption(CURLOPT_HEADER, 0);
            $this->curl->setOption(CURLOPT_TIMEOUT, 0);
            $this->curl->setOption(CURLOPT_MAXREDIRS, 10);
            $this->curl->setOption(CURLOPT_RETURNTRANSFER, true);
            $this->curl->addHeader("Content-Type", "application/json");
            $this->curl->addHeader("Accept", "application/json");
            $this->curl->get(self::CARBONCLICK_CONFIG_URL.'api/countries');
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

    public function getStoreCountry(){
        $paymentCountry = $this->getConfig('payment/account/merchant_country');
        if($paymentCountry){
            return $paymentCountry;
        }
        return $this->getConfig('general/country/default');
    }
}
