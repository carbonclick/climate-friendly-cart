<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Carbonclick\CFC\Plugin\Rule\Model;

use Magento\Framework\Session\SessionManager;
 
class AbstractCondition
{

    private $helper;
 
    
    public function __construct(
        \Carbonclick\CFC\Helper\Email $helper
    ) {
        $this->helper = $helper;
    }

    public function aroundValidate(
        \Magento\Rule\Model\Condition\AbstractCondition $subject,
        \Closure $proceed,
        $model
    ) {
        if ($this->helper->getConfig('cfc/general/enable') == 1 && $this->helper->getConfig('cfc/general/product')) {
            if ($subject->getAttribute()) {
                if ($model instanceof \Magento\Quote\Model\Quote\Address) {
                    $subtotal = $model->getBaseSubtotal();
                    $totalQty = $model->getTotalQty();
                    $subwithdiscount = $model->getBaseSubtotalWithDiscount();
                    $items = $model->getAllVisibleItems();
                    foreach ($items as $item) {
                        if ($item->getProductId() == $this->helper->getConfig('cfc/general/product')) {
                            $model->setBaseSubtotal($subtotal - $item->getBaseRowTotal());
                            $model->setTotalQty($totalQty - $item->getQty());
                            $model->setBaseSubtotalWithDiscount($subwithdiscount - $item->getBaseRowTotal());
                        }
                    }

                    $result = $proceed($model);
                    $model->setBaseSubtotal($subtotal);
                    $model->setTotalQty($totalQty);
                    $model->setBaseSubtotalWithDiscount($subwithdiscount);
                } else {
                    $result = $proceed($model);
                }
                
            } else {
                $result = $proceed($model);
            }
        } else {
            $result = $proceed($model);
        }
        return $result;
    }
}
