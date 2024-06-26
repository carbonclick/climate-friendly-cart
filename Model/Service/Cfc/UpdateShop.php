<?php

namespace Carbonclick\CFC\Model\Service\Cfc;

class UpdateShop extends CreateShop
{

    public function UpdateShop($params = [])
    {
        $shopdata = $this->SendUpdateShopRequest($params);
        if ($shopdata) {
            return $this->jsonHelper->jsonDecode($shopdata);
        }
        return;
    }

    private function SendUpdateShopRequest($params)
    {
        $shop = $this->getShop();
        // DO NOT CHANGE THE VALUE OF THIS PARAMATER. Our make script performs a search/replace on this to insert the build pipeline number.
        $params['version'] = "1.1.117";

        try {
            $this->curl->setOption(CURLOPT_TIMEOUT, 60);
            $this->curl->addHeader("Content-Type", "application/json");
            $this->curl->addHeader("Accept", "application/json");
            $this->curl->addHeader("Authorization", "Bearer ".$shop['access_token']);
            $this->curl->put($this->getCarbonConfigUrl().'api/shops/edit', $this->jsonHelper->jsonEncode($params));
            $response = $this->curl->getBody();
            if ($this->curl->getStatus() == 200) {
                return $response;
            } else {
                throw new \Exception($response);
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            $this->messageManager->addError($e->getMessage());
            return;
        }
        return;
    }
}
