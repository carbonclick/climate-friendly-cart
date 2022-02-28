<?php

namespace Carbonclick\CFC\Model\Service\Cfc;

class UpdateStatus extends CreateShop
{

    public function SendUpdateStatusRequest()
    {
        $shop = $this->getShop();
        $params['status'] = "uninstall";
        $params['setup'] = $this->getConfig("cfc/general/enable") == 1 ? true : false;

        try {
            $this->curl->setOption(CURLOPT_TIMEOUT, 60);
            $this->curl->addHeader("Content-Type", "application/json");
            $this->curl->addHeader("Accept", "application/json");
            $this->curl->addHeader("Authorization", "Bearer ".$shop['access_token']);
            $this->curl->post($this->getCarbonConfigUrl().'api/shops/status', $this->jsonHelper->jsonEncode($params));
            $response = $this->curl->getBody();
            if ($this->curl->getStatus() == 200) {
                return $response;
            } else {
                throw new \Exception($response);
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            //$this->messageManager->addError($e->getMessage());
            return;
        }
        return;
    }
}
