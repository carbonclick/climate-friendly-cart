<?php

namespace Carbonclick\CFC\Block\Cart;

use Carbonclick\CFC\Model\Service\Cfc\Impactall;
use Magento\Catalog\Api\ProductRepositoryInterface;

class Carbonclick extends \Magento\Framework\View\Element\Template
{

    protected $impactall;

    protected $helper;

    protected $productRepository;

    protected $priceHelper;
    
    protected $cartHelper;

    protected $updateshop;

    /**
     * @param \Magento\Backend\Block\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Carbonclick\CFC\Helper\Email $helper,
        Impactall $impactall,
        ProductRepositoryInterface $productRepository,
        \Magento\Framework\Pricing\Helper\Data $priceHelper,
        \Magento\Checkout\Helper\Cart $cartHelper,
        \Carbonclick\CFC\Model\Service\Cfc\UpdateShop $updateshop,
        array $data = []
    )
    {
        $this->helper = $helper;
        $this->impactall = $impactall;
        $this->productRepository = $productRepository;
        $this->priceHelper = $priceHelper;
        $this->cartHelper = $cartHelper;
        $this->updateshop = $updateshop;
        parent::__construct($context, $data);
    }

    public function getHelper(){
        return $this->helper;
    }

    public function getImpactData(){
       return $this->impactall->getImpactAlldata(false);
    }

    public function ConvertToStoreWeight($weight,$unit){
        $weightUnit = $this->getHelper()->getConfig("general/locale/weight_unit");
        if($weightUnit == "kgs"){
            return ['<span class="cfc-stat-number">'.number_format($weight,0)."</span> ",$unit];
        }elseif($weightUnit == "lbs"){
            return ['<span class="cfc-stat-number">'.number_format($weight*2.20462262185,0)."</span> ",$weightUnit];
        }
        return ['<span class="cfc-stat-number">'.number_format($weight,0)."</span> ",$unit];

    }

    public function getProduct(){
        return $product = $this->productRepository->getById($this->getProductId());
    }

    public function getProductId(){
        return $this->getHelper()->getConfig("cfc/general/product");
    }

    public function getPriceHelper(){
        return $this->priceHelper;
    }

    public function getAddtocartUrl($product){
        return $this->cartHelper->getAddUrl($product,[]);
    }

    public function getCarbonProductFromCart($product){
        return $this->cartHelper->getQuote()->getItemByProduct($product);
    }

    public function getRemoveCartUrl($item){
        return $this->cartHelper->getDeletePostJson($item);
    }

    public function UpdateCartImpression(){
        return $this->updateshop->UpdateShop(['last_impression'=>true]);
    }
}
