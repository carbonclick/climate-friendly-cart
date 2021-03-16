<?php

namespace Carbonclick\CFC\Observer;

use Carbonclick\CFC\Model\Service\Cfc\Refund;
use Carbonclick\CFC\Helper\Email;
use Magento\Framework\Event\ObserverInterface;

class CancelOrderObserver implements ObserverInterface
{

	protected $refund;

	protected $helper;

	protected $_logger;

	public function __construct(
		Refund $refund,
		Email $helper,
		\Psr\Log\LoggerInterface $logger
	){
		$this->refund = $refund;
		$this->helper = $helper;
		$this->_logger = $logger;
	}

	public function execute(\Magento\Framework\Event\Observer $observer)
	{
	    $order = $observer->getEvent()->getOrder();
	    if(empty($this->helper->getConfig('cfc/general/shop'))){
        	return $this;
        }
	    $productId = $this->helper->getConfig('cfc/general/product');
	    foreach ($order->getAllVisibleItems() as $item) {
	    	if($item->getProductId() == $productId){
	    		$params = [
					"number"=> $order->getEntityId(),
					"cancel_reason"=> "Order Cancelled",
			    ];
			    $this->_logger->log(100,print_r($params,true));
			    $this->refund->sendRefundRequest($params);
	    		break;
	    	}
	    };
	    return $this;
	}
}
