<?php
namespace Carbonclick\CFC\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\Escaper;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\ProductMetadataInterface;

class Email extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $inlineTranslation;
    protected $escaper;
    protected $transportBuilder;
    protected $logger;
    protected $request;
    protected $productMetadata;

    public function __construct(
        Context $context,
        StateInterface $inlineTranslation,
        Escaper $escaper,
        Http $request,
        ProductMetadataInterface $ProductMetadata,
        TransportBuilder $transportBuilder
    ) {
        parent::__construct($context);
        $this->inlineTranslation = $inlineTranslation;
        $this->escaper = $escaper;
        $this->request = $request;
        $this->transportBuilder = $transportBuilder;
        $this->productMetadata = $ProductMetadata;
        $this->logger = $context->getLogger();
    }

    public function sendEmail()
    {
        try {
            $this->inlineTranslation->suspend();
            $sender = [
                'name' => $this->escaper->escapeHtml('CarbonClick'),
                'email' => $this->escaper->escapeHtml('info@carbonclick.com'),
            ];
            $transport = $this->transportBuilder
                ->setTemplateIdentifier('carbonclick_cfc_help')
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                        'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                    ]
                )
                ->setTemplateVars([
                    'name'  => $this->getConfig('trans_email/ident_general/name'),
                    'email' => $this->getConfig('trans_email/ident_general/email')
                ])
                ->setFrom($sender)
                ->addTo('urvish.brightness@gmail.com')
                ->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
        } catch (\Exception $e) {            
            $this->logger->debug($e->getMessage());
            return;
        }
    }

    public function getConfig($config_path)
    {
        return $this->scopeConfig->getValue(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getModuleDisabled()
    {
        if ($this->request->getParam('carbonclick') == true) {
            return false;
        }
        if ($this->getConfig('cfc/general/shop')) {
            return !$this->getConfig('cfc/general/enable');
        }

        return true;
    }

    public function getCartpageDisable()
    {
        if ($this->getModuleDisabled() != true) {
            $location = $this->getAvailableLocation();
            if (in_array('cart', $location)) {
                return false;
            }
        }
        return true;
    }

    public function getMiniCartpageDisable()
    {
        if ($this->getModuleDisabled() != true) {
            $location = $this->getAvailableLocation();
            if (in_array('mini_cart', $location)) {
                return false;
            }
        }
        return true;
    }


    public function getCheckoutpageDisable()
    {
        if ($this->getModuleDisabled() != true) {
            $location = $this->getAvailableLocation();
            if (in_array('checkout', $location)) {
                return false;
            }
        }
        return true;
    }

    public function getAvailableLocation()
    {
        $location = $this->getConfig('cfc/general/widget_location');
        return explode(",", $location);
    }

    /**
     * Added line 141 to 142 for minicart load in magento version 2.3.7-p2
     *
     * @return void
     */
    public function getTemplateConfig()
    {
        if ($this->getMiniCartpageDisable()) {
            return 'Magento_Checkout/minicart/content';
        }
        if ($this->getConfig('cfc/general/enable') == 1) {
            if (version_compare($this->getMagentoVersion(), "2.3.7-p3", "<=")) {
                return 'Carbonclick_CFC/minicart/content234'; 
            } elseif (version_compare($this->getMagentoVersion(), "2.4.4", "<=")) {
                return 'Carbonclick_CFC/minicart/content';
            }
        }

        return 'Magento_Checkout/minicart/content';
    }

    public function getMagentoVersion()
    {
        return $this->productMetadata->getVersion();
    }
}
