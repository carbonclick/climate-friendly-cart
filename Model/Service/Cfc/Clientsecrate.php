<?php

namespace Carbonclick\CFC\Model\Service\Cfc;


class Clientsecrate extends Authentication
{
    public function Clientsecrate(){
		$key = $this->SendClientsecrateRequest();
		if($key){
            $keyresponse = $this->jsonHelper->jsonDecode($key);
            return $keyresponse;
		}
    	return;
    }

    private function SendClientsecrateRequest(){

        $params = [
            'type'=>'magento',
            'domain'=>$this->getConfig("web/secure/base_url")
        ];

    	try{
            $this->curl->setOption(CURLOPT_TIMEOUT, 60);
            $this->curl->addHeader("Content-Type", "application/json");
            $this->curl->addHeader("Accept", "application/json");
            $this->curl->post(self::CARBONCLICK_CONFIG_URL.'api/stripe/setup-intent',$this->jsonHelper->jsonEncode($params));
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
