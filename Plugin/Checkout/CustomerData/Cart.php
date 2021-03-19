<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Carbonclick\CFC\Plugin\Checkout\CustomerData;
 
class Cart
{
    protected $carbonclick;

    public function __construct(
        \Carbonclick\CFC\Block\Cart\AdditionalConfigVars $carbonclick
    ) {
        $this->carbonclick = $carbonclick;
    }


    public function afterGetSectionData(\Magento\Checkout\CustomerData\Cart $subject, array $result)
    {
        $block = $this->carbonclick->getConfig();

        if(empty($block)){
            return $result;
        }

        $result['carbonclick_data'] = $block['carbonclick_data'];

        return $result;
    }
}