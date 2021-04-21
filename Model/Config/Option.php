<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Carbonclick\CFC\Model\Config;

class Option implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {

        return  [
                    [
                        'value' => 'standard',
                        'label' => __('Standard')
                    ],
                    [
                        'value' => 'black',
                        'label' => __('Black')
                    ],
                    [
                        'value' => 'white',
                        'label' => __('White')
                    ]
                ];
    }
}
