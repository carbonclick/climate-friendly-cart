<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Carbonclick\CFC\Model\Config;

class EnvironmentOption implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {

        return  [
                    [
                        'value' => 1,
                        'label' => __('Live')
                    ],
                    [
                        'value' => 2,
                        'label' => __('Dev')
                    ]
                ];
    }
}
