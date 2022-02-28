<?php

namespace Carbonclick\CFC\Observer;

use Carbonclick\CFC\Model\Service\Cfc\Refund;
use Carbonclick\CFC\Helper\Email;
use Magento\Framework\Event\ObserverInterface;

class CreditmemoOrderObserver implements ObserverInterface
{

    protected $refund;

    protected $helper;

    protected $_logger;

    public function __construct(
        Refund $refund,
        Email $helper,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->refund = $refund;
        $this->helper = $helper;
        $this->_logger = $logger;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try{
            $event = $observer->getEvent();

            if ($event->hasData('creditmemo')) {
                /** @var \Magento\Sales\Model\Order\Creditmemo $creditmemo */
                $creditmemo = $event->getData('creditmemo');
                if (empty($this->helper->getConfig('cfc/general/shop'))) {
                    return $this;
                }
                $productId = $this->helper->getConfig('cfc/general/product');
                foreach ($creditmemo->getAllItems() as $item) {
                    if ($item->getProductId() == $productId) {
                        $params = [
                            "number"=> $creditmemo->getOrder()->getId(),
                            "cancel_reason"=> "Creditmemo Generated",
                        ];
                        //$this->_logger->log(100, print_r($params, true));
                        $this->refund->sendRefundRequest($params);
                        break;
                    }
                }
            }
        }catch(\Exception $e){
            $this->_logger->log(100, print_r($e->getMessage(), true));
        }
        return $this;
    }
}
