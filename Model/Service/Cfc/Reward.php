<?php

namespace Carbonclick\CFC\Model\Service\Cfc;

class Reward extends CreateShop
{

    public function RedeemReward($params = [])
    {
        $rewarddata = $this->SendRewardRequest($params);
        if ($rewarddata) {
            return $this->jsonHelper->jsonDecode($rewarddata);
        }
        return;
    }

    private function SendRewardRequest($params)
    {
        $shop = $this->getShop();

        try {
            $this->curl->setOption(CURLOPT_TIMEOUT, 60);
            $this->curl->addHeader("Content-Type", "application/json");
            $this->curl->addHeader("Accept", "application/json");
            $this->curl->addHeader("Authorization", "Bearer ".$shop['access_token']);
            $this->curl->put($this->getCarbonConfigUrl().'api/rewards/redeem', $this->jsonHelper->jsonEncode($params));
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
