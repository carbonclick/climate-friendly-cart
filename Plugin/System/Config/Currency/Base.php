<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Carbonclick\CFC\Plugin\System\Config\Currency;
 
class Base
{
    private $configSave;

    protected $cfcproduct;

    protected $updateshop;

    private $currency = ['EUR', 'NZD', 'CAD', 'USD', 'GBP', 'AUD','ZAR'];

    public function __construct(
        \Carbonclick\CFC\Model\CreateProduct $cfcproduct,
        \Carbonclick\CFC\Model\Service\Cfc\UpdateShop $updateshop,
        \Carbonclick\CFC\Model\Service\SaveDashboard $configSave
    ) {
        $this->configSave = $configSave;
        $this->cfcproduct = $cfcproduct; 
        $this->updateshop = $updateshop;
    }

    public function afterAfterSave(
        \Magento\Config\Model\Config\Backend\Currency\Base $subject,
        $result
    ) {
        if(!in_array($subject->getValue(), $this->currency)){
            $this->configSave->saveConfig('cfc/general/enable',0);
            $this->cfcproduct->UpdateStatus(0);
            $this->updateshop->UpdateShop(['setup'=>false]);
        }
        return $result;
    }
}