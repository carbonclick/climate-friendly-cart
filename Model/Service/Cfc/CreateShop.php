<?php

namespace Carbonclick\CFC\Model\Service\Cfc;

class CreateShop extends Authentication
{

    public function getShop()
    {
        $shopdata = $this->getConfig('cfc/general/shop');
        if ($shopdata) {
            return $this->jsonHelper->jsonDecode($shopdata);
        }
        return;
    }

    public function CreateShop($stripetoken)
    {
        $shopdata = $this->SendCreateShopRequest($stripetoken);
        if ($shopdata) {
            $shopdataresponse = $this->jsonHelper->jsonDecode($shopdata);
            if ($shopdataresponse['success'] == false) {
                $this->messageManager->addError($shopdataresponse['message']);
            } else {
                $this->configWriter->save('cfc/general/shop', $this->jsonHelper->jsonEncode($shopdataresponse));
                if ($shopdataresponse['mode'] == "postpaid") {
                    $this->configWriter->save('cfc/general/prepaid', 0);
                    $this->configWriter->save('cfc/general/postpaid', 1);
                } else {
                    $this->configWriter->save('cfc/general/prepaid', 1);
                    $this->configWriter->save('cfc/general/postpaid', 0);
                }
                $this->refreshCache();
                return $shopdataresponse;
            }
            
        }
        return;
    }

    private function SendCreateShopRequest($stripetoken)
    {
        
        $storename = $this->getConfig("general/store_information/name");
        if (empty($storename)) {
            $user = $this->authSession->create()->getUser();
            if ($user) {
                $storename = $user->getFirstname().' '.$user->getLastname() ;
            } else {
                $storename = $this->getConfig("web/secure/base_url");
            }
            
        }

        $params = [
            'type'=>'magento',
            'domain'=>$this->getConfig("web/secure/base_url"),
            'name'=> $storename,
            'shop_owner'=> $storename,
            'email'=>$this->getConfig("trans_email/ident_general/email"),
            'currency'=> $this->getCurrencyCode(),
            'country_code'=> $this->getConfig('general/country/default'),
            'timezone'=> $this->getConfig("general/locale/timezone"),
            'primary_locale'=> $this->getConfig("general/locale/code"),
            'weight_unit'=>$this->getConfig("general/locale/weight_unit"),
            'install_help_required'=>false,
            'description'=>""
        ];

        if ($stripetoken == "postpaid") {
            $params['mode'] = "postpaid";
        } else {
            $params['setupintent_id'] = $stripetoken;
            $params['mode'] = "prepaid";
        }

        try {
            $this->curl->setOption(CURLOPT_TIMEOUT, 60);
            $this->curl->addHeader("Content-Type", "application/json");
            $this->curl->addHeader("Accept", "application/json");
            $this->curl->post(self::CARBONCLICK_CONFIG_URL.'api/shops', $this->jsonHelper->jsonEncode($params));
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

    private function getCurrencyCode()
    {
        return $this->storeManager->getStore()->getBaseCurrencyCode();
    }
}
