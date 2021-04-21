<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Carbonclick\CFC\Api;

/**
 * Interface PaymentfailedInterface
 * @api
 */
interface PaymentfailedInterface
{
    /**
     * Payment failure Update
     *
     * @param string $type
     * @param string $message
     * @return bool
     */
    public function update($type, $message);
}
