<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Carbonclick\CFC\Model\Config\Backend;

class Enable extends \Magento\Framework\App\Config\Value
{

    /**
     * @var \Carbonclick\CFC\Model\CreateProduct
     */
    protected $cfcproduct;

    protected $updateshop;

    private $currency = ['EUR', 'NZD', 'CAD', 'USD', 'GBP', 'AUD','ZAR'];

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
             $this->cfcproduct->UpdateStatus($this->getValue());
             $value = $this->getValue() == 1? true: false;
             $this->updateshop->UpdateShop(['setup'=>$value]);
        }
        return parent::afterSave();
    }

    /**
     * Processing object before save data
     *
     * @return $this
     */
    public function beforeSave()
    {
        if($this->getValue() == 1 && empty($this->updateshop->getShop())){
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Please complete Onboarding Process before enable the extension.')
            );
        }

        if($this->getValue() == 1 && !in_array($this->updateshop->getConfig('currency/options/base'), $this->currency)){
            throw new \Magento\Framework\Exception\LocalizedException(
                __('We are only supported EUR, USD, AUD, CAD, NZD, GBP and ZAR currencies.')
            );    
        }

        if($this->getValue() == 1){
            $response = $this->updateshop->UpdateShop(['setup'=>true]);
            if($response['success'] == false && $response['error'] == "blocked"){
                throw new \Magento\Framework\Exception\LocalizedException(
                    __($response['message'])
                );
            }
        }

        return parent::beforeSave();
    }

}