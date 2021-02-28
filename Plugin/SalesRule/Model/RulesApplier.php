<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Carbonclick\CFC\Plugin\SalesRule\Model;

use Magento\Framework\Session\SessionManager;
 
class RulesApplier
{
    /**
     * @var \Magento\SalesRule\Model\ResourceModel\Rule\Collection
     */
    private $ruleCollection;

    private $helper;
 
    /**
     * @param \Magento\SalesRule\Model\ResourceModel\Rule\Collection $rules
     */
    public function __construct(
        \Magento\SalesRule\Model\ResourceModel\Rule\CollectionFactory $rulesFactory,
        \Carbonclick\CFC\Helper\Email $helper
    ) {
        $this->ruleCollection = $rulesFactory;
        $this->helper = $helper;
    }
 
    public function aroundApplyRules(
        \Magento\SalesRule\Model\RulesApplier $subject,
        \Closure $proceed,
        $item,
        $rules,
        $skipValidation,
        $couponCode
    ) {
        if($this->helper->getConfig('cfc/general/enable') == 1 && $this->helper->getConfig('cfc/general/product')){
            if($item->getProductId() == $this->helper->getConfig('cfc/general/product')){
                $rules = $this->ruleCollection->create()->addFieldToSelect('*')->addFieldToFilter("coupon_type", ["eq"=>1])->addFieldToFilter("is_active", ["eq"=>1]); 
            }
        }
        $result = $proceed($item, $rules, $skipValidation, $couponCode);
        return $result;
    }
}