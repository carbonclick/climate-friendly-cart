<?php

namespace Carbonclick\CFC\Model\Service\Cfc;


class Purchases extends CreateShop
{
	public function sendPurchaseRequest($params){
		$shop = $this->getShop();
		try{
            $this->curl->setOption(CURLOPT_TIMEOUT, 60);
            $this->curl->addHeader("Content-Type", "application/json");
            $this->curl->addHeader("Accept", "application/json");
            $this->curl->addHeader("Authorization", "Bearer ".$shop['access_token']);
            $this->curl->post(self::CARBONCLICK_CONFIG_URL.'api/purchases',$this->jsonHelper->jsonEncode($params));
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
