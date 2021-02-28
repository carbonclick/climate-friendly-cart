<?php

namespace Carbonclick\CFC\Model\Service\Cfc;


class UpdateCard extends CreateShop
{

    public function getUpdatedcard($stripetoken){
        $updatecard = $this->SendUpdateCardRequest($stripetoken);
        if($updatecard){
            return $this->jsonHelper->jsonDecode($updatecard);
        }
        return;
    }

    private function SendUpdateCardRequest($stripetoken){
        $shop = $this->getShop();
        $params = [
            'setupintent_id'=>$stripetoken
        ];
        print_r($params);
    	try{
            $this->curl->setOption(CURLOPT_TIMEOUT, 60);
            $this->curl->addHeader("Content-Type", "application/json");
            $this->curl->addHeader("Accept", "application/json");
            $this->curl->addHeader("Authorization", "Bearer ".$shop['access_token']);
            $this->curl->put(self::CARBONCLICK_CONFIG_URL.'api/shops/card',$this->jsonHelper->jsonEncode($params));
            $response = $this->curl->getBody();
            if($this->curl->getStatus() == 200){
                return $response;
            }else{
                throw new \Exception($response);
            }
        }catch(\Exception $e){
            echo $e->getMessage(); die;
            $this->logger->error($e->getMessage());
            return;
        }
        return;
    }

    private function getCurrencyCode(){
        return $this->storeManager->getStore()->getBaseCurrencyCode();
    }
}
