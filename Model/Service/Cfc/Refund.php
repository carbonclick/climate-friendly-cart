<?php

namespace Carbonclick\CFC\Model\Service\Cfc;

class Refund extends CreateShop
{
    public function sendRefundRequest($params)
    {
        $shop = $this->getShop();
        try {
            $this->curl->setOption(CURLOPT_TIMEOUT, 60);
            $this->curl->addHeader("Content-Type", "application/json");
            $this->curl->addHeader("Accept", "application/json");
            $this->curl->addHeader("Authorization", "Bearer ".$shop['access_token']);
            $this->curl->put(self::CARBONCLICK_CONFIG_URL.'api/purchases/refund', $this->jsonHelper->jsonEncode($params));
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
