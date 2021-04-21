<?php

namespace Carbonclick\CFC\Observer;

use Carbonclick\CFC\Model\Service\Cfc\Purchases;
use Carbonclick\CFC\Model\Service\Cfc\UpdateShop;
use Carbonclick\CFC\Helper\Email;
use Magento\Framework\Event\ObserverInterface;
use Magento\Directory\Model\CountryFactory;
use Magento\Framework\UrlInterface;
use Magento\Sales\Model\ResourceModel\Order;

class OrderObserver implements ObserverInterface
{

    protected $purchases;

    protected $updateshop;

    protected $helper;

    protected $countryfactory;

    protected $OrderResource;

    protected $urlinterface;

    protected $_logger;

    public function __construct(
        Purchases $purchases,
        UpdateShop $updateshop,
        Order $OrderResource,
        Email $helper,
        CountryFactory $countryfactory,
        UrlInterface $UrlInterface,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->purchases = $purchases;
        $this->updateshop = $updateshop;
        $this->helper = $helper;
        $this->countryfactory = $countryfactory;
        $this->OrderResource = $OrderResource;
        $this->urlinterface = $UrlInterface;
        $this->_logger = $logger;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        if (!$order->getId()) {
            //order not saved in the database
            return $this;
        }

        if (empty($this->updateshop->getShop())) {
            return $this;
        }
        
        if (!is_null($order->getCarbonclickSyncFailed()) && ($order->getCarbonclickSyncFailed() == 0 || $order->getCarbonclickSyncFailed() == 1)) {
            return $this;
        }
        $productId = $this->helper->getConfig('cfc/general/product');
        $showcart = [];
        foreach ($order->getAllVisibleItems() as $item) {
            if ($item->getProductId() == $productId) {
                $shippingaddress = $order->getShippingAddress();
                $billingaddress = $order->getBillingAddress();
                $street = $billingaddress->getStreet();
                $preferred_topup = $this->helper->getConfig('cfc/general/topup');
                if ($item->getBaseRowTotal() > $preferred_topup) {
                    $preferred_topup = $item->getBaseRowTotal();
                }
                $params = [
                    "email" => $order->getCustomerEmail(),
                    "name"=> "#".$order->getIncrementId(),
                    "number"=> $order->getEntityId(),
                    "phone"=>  $shippingaddress->getTelephone(),
                    "price"=> $item->getBasePrice(),
                    "currency"=> $order->getBaseCurrencyCode(),
                    "quantity"=> (int)$item->getQtyOrdered(),
                    "tax"=> $item->getBaseTaxAmount(),
                    "total_price"=> $item->getBaseRowTotal(),
                    "order_status_url"=> $this->urlinterface->getUrl('sales/order/view', ["order_id"=>$order->getEntityId()]),
                    "gateway"=> $order->getPayment()->getMethodInstance()->getTitle(),
                    "city"=> $shippingaddress->getCity(),
                    "country"=> $this->getCountryname($shippingaddress->getCountryId()),
                    "preferred_topup"=> $preferred_topup,
                    "billing_address"=> [
                        "zip"=> $billingaddress->getPostcode(),
                        "city"=> $billingaddress->getCity(),
                        "name"=> $billingaddress->getName(),
                        "phone"=> $billingaddress->getTelephone(),
                        "company"=> $billingaddress->getCompany(),
                        "country"=> $this->getCountryname($billingaddress->getCountryId()),
                        "address1"=> count($street) > 0 ? $street[0] : "",
                        "address2"=> count($street) > 1 ? $street[1] : "",
                        "province"=> $billingaddress->getRegion(),
                        "last_name"=> $billingaddress->getLastname(),
                        "first_name"=> $billingaddress->getFirstname(),
                        "country_code"=> $billingaddress->getCountryId(),
                        "province_code"=> $billingaddress->getRegionCode()
                    ]
                ];
                //$this->_logger->log(100,print_r($params,true));
                   $purchase = $this->purchases->sendPurchaseRequest($params);
                if ($purchase) {
                    $order->setCarbonclickSyncFailed(0);
                } else {
                    $order->setCarbonclickSyncFailed(1);
                }
                   $this->OrderResource->saveAttribute($order, 'carbonclick_sync_failed');
                $showcart['last_impression'] = true;
                break;
            }
        }
        $this->updateshop->UpdateShop($showcart);
        $this->_logger->log(100, print_r($params, true));
        return $this;
    }

    public function getCountryname($countryCode)
    {
        $country = $this->countryfactory->create()->loadByCode($countryCode);
        return $country->getName();
    }
}
