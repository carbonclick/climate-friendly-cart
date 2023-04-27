<?php
/**
 * Switch between sandbox and live environment.
 */
namespace Carbonclick\CFC\Model\Config\Backend;

class Environment extends \Magento\Framework\App\Config\Value
{

    /**
     * @var \Carbonclick\CFC\Model\CreateProduct
     */
    protected $createproduct;

    /**
     * @var \Carbonclick\CFC\Model\Service\SaveDashboard
     */    
    protected $saveconfig;

    protected $createshop;

        /**
     * @var State
     */
    private $state;

    
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        \Carbonclick\CFC\Model\CreateProduct $createproduct,
        \Carbonclick\CFC\Model\Service\Cfc\CreateShop $createshop,
        \Carbonclick\CFC\Model\Service\SaveDashboard $saveconfig,
        \Magento\Framework\App\State $state,        
        \Psr\Log\LoggerInterface $logger,
        array $data = []
    ) {        
        $this->logger = $logger;
        $this->createproduct = $createproduct;
        $this->saveconfig = $saveconfig;
        $this->createshop = $createshop;
        $this->state = $state;

        parent::__construct(
            $context,
            $registry,
            $config,
            $cacheTypeList,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * @return $this
     */
    public function afterSave()
    {
        if ($this->isValueChanged()) { 
            $this->saveconfig->saveConfig('cfc/general/mode', $this->getValue());

            $postPaid = $this->saveconfig->getConfig('cfc/general/postpaid');

            $postPaid = $this->saveconfig->getConfig('cfc/general/postpaid');
            if ($postPaid == 1) {
                $this->logger->info('Creating shop for postpaid');
                // If postpaid is enabled in the environment we're switching from, create postpaid shop in environment we're switching to.
                $shop = $this->createshop->CreateShop("postpaid");
                if (!empty($shop)) {
                    $this->createproduct->CreateProduct();
                }
            } else {
                $this->logger->info('Invalidating Prepaid Shop');
                // If prepaid, clear shop token, disable module and force the user to complete onboarding with credit card again.
                $this->saveconfig->saveConfig('cfc/general/shop','');
                $this->saveconfig->saveConfig('cfc/general/enable',0);
                $this->createproduct->UpdateStatus(0);                
            }
    
        }
        
        return parent::afterSave();
    }

    /**
     * Processing object before save data
     *
     * @return $this
     */
    public function beforeSave()
    {
        return parent::beforeSave();
    }
}
