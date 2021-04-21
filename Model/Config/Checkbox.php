<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Carbonclick\CFC\Model\Config;

class Checkbox implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {

        return  [
                    [
                        'value' => 'cart',
                        'label' => __('Most themes have this page which lets user review their cart prior to checkout'),
                        'header_text' => __('Cart Page')
                    ],
                    [
                        'value' => 'mini_cart',
                        'label' => __('Also called drawer-cart, some themes have this feature'),
                        'header_text' => __('Mini Cart')
                    ],
                    [
                        'value' => 'checkout',
                        'label' => __('This is the payment page'),
                        'header_text' => __('Checkout')
                    ]
                ];
    }
}
