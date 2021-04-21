<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Carbonclick\CFC\Model\Config\Backend;

class Topup extends \Magento\Framework\App\Config\Value
{

    /**
     * @var \Carbonclick\CFC\Model\CreateProduct
     */
    protected $_config;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        \Carbonclick\CFC\Model\CreateProduct $cfcproduct,
        \Carbonclick\CFC\Model\Service\Cfc\UpdateShop $updateshop,
        array $data = []
    ) {
       
        $this->cfcproduct = $cfcproduct;
        $this->updateshop = $updateshop;
        parent::__construct(
            $context,
            $registry,
            $config,
            $cacheTypeList,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * @return $this
     */
    public function afterSave()
    {
        if ($this->isValueChanged()) {
             $this->updateshop->UpdateShop(['preferred_topup'=>$this->getValue()]);
        }
        return parent::afterSave();
    }
}
