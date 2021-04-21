<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Carbonclick\CFC\Plugin\System\Config;
 
class Edit
{
    private $helper;

    private $messageManager;

    public function __construct(
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Carbonclick\CFC\Helper\Email $helper
    ) {
        $this->helper = $helper;
        $this->messageManager = $messageManager;
    }

    public function beforeExecute(
        \Magento\Config\Controller\Adminhtml\System\Config\Edit $subject
    ) {
        if ($subject->getRequest()->getParam('section') == "cfc") {
            $notice = $this->helper->getConfig('cfc/general/payment');
            if ($notice) {
                $this->messageManager->addNotice($notice);
            }
            $invoicenotice = $this->helper->getConfig('cfc/general/invoice');
            if ($invoicenotice) {
                $this->messageManager->addNotice($invoicenotice);
            }
        }
        return;
    }
}
