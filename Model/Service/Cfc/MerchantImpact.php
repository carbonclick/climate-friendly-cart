<?php

namespace Carbonclick\CFC\Model\Service\Cfc;

class MerchantImpact extends CreateShop
{

    public function getMerchantImpact()
    {
        $impactdata = $this->SendMerchantImpactRequest();
        if ($impactdata) {
            return $this->jsonHelper->jsonDecode($impactdata);
        }
        return;
    }

    private function SendMerchantImpactRequest()
    {
        $shop = $this->getShop();
        try {
            $this->curl->setOption(CURLOPT_HEADER, 0);
            $this->curl->setOption(CURLOPT_TIMEOUT, 0);
            $this->curl->setOption(CURLOPT_MAXREDIRS, 10);
            $this->curl->setOption(CURLOPT_RETURNTRANSFER, true);
            $this->curl->addHeader("Authorization", "Bearer ".$shop['access_token']);
            $this->curl->addHeader("Content-Type", "application/json");
            $this->curl->addHeader("Accept", "application/json");
            $this->curl->get(self::CARBONCLICK_CONFIG_URL.'api/carbonclick/impacts/merchant');
            $response = $this->curl->getBody();
            if ($this->curl->getStatus() == 200) {
                return $response;
            } else {
                throw new \Exception($response);
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            return;
        }
        return;
    }
}
