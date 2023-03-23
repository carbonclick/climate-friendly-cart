<?php

namespace Carbonclick\CFC\Block\Adminhtml;

class Dashboard extends \Magento\Backend\Block\Template
{
    protected $fetchcustomer;

    protected $impactall;

    protected $orderCollectionFactory;

    protected $priceHelper;

    protected $merchantImpact;

    /**
     * @param \Magento\Backend\Block\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Carbonclick\CFC\Model\Service\Cfc\FetchCustomer $fetchcustomer,
        \Carbonclick\CFC\Model\Service\Cfc\Impactall $Impactall,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Framework\Pricing\Helper\Data $priceHelper,
        \Carbonclick\CFC\Model\Service\Cfc\MerchantImpact $merchantImpact,
        array $data = []
    ) {
        $this->fetchcustomer = $fetchcustomer;
        $this->impactall = $Impactall;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->priceHelper = $priceHelper;
        $this->merchantImpact = $merchantImpact;
        parent::__construct($context, $data);
    }

    public function getFetchcustomer()
    {
        return $this->fetchcustomer->getCustomerInfo();
    }

    public function getImpactall()
    {
        return $this->impactall->getImpactAlldata();
    }

    public function getTotalOrder()
    {
        $ordercollection = $this->orderCollectionFactory->create();
        return $ordercollection->getSize();
    }

    public function getOrderwithOffset($offsetOrders)
    {
        if ($this->getTotalOrder() > 0) {
            return $offsetOrders * 100 / $this->getTotalOrder();
        }
        return 0;
    }

    public function getPriceHelper()
    {
        return $this->priceHelper;
    }

    public function getMerchantImpact()
    {
        return $this->merchantImpact->getMerchantImpact();
    }

    public function getConfig($path)
    {
        return $this->merchantImpact->getConfig("cfc/dashboard/".$path);
    }

    public function ConvertToStoreWeight($weight, $unit)
    {
        $weightUnit = $this->merchantImpact->getConfig("general/locale/weight_unit");
        if ($weight == 0) {
            return 'N/A';
        }

        if ($weightUnit == "kgs") {
            return number_format($weight, 0)." ".$unit;
        } elseif ($weightUnit == "lbs") {
            return number_format($weight*2.20462262185, 0)." ".strtoupper($weightUnit);
        }
        return number_format($weight, 0)." ".$unit;
    }

    public function getfullpathConfig($path)
    {
        return $this->merchantImpact->getConfig($path);
    }
}
