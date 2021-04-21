<?php

namespace Carbonclick\CFC\Block\Cart;

use \Magento\Checkout\Model\ConfigProviderInterface;

class AdditionalConfigVars extends Carbonclick implements ConfigProviderInterface
{
    public function getConfig()
    {
        if (is_null($this->getProductId())) {
            return [];
        }
        $helper = $this->getHelper();
        $impactalldata = $this->getImpactData();
        $product = $this->getProduct();
        $priceHelper = $this->getPriceHelper();
        $cartitem = $this->getCarbonProductFromCart($product);
        $co2weight = $this->ConvertToStoreWeight($impactalldata['carbonOffsetImpact']['value'], $impactalldata['carbonOffsetImpact']['unit']);
        $iconcolor = $helper->getConfig('cfc/lookandfeel/plugincolors/icons_color');
        $cfclogo = $helper->getConfig('cfc/lookandfeel/cfclogo/color_option');
        $filename = "carbonclick-logo-".$cfclogo."-picker.svg";
        $showdecimal = true;
        if ($product->getPrice() == intval($product->getPrice())) {
            $showdecimal = false;
        }
        $price = $priceHelper->currency($product->getPrice(), true, false);

        $additionalVariables['carbonclick_data'] = [
            'icon_colour' => $iconcolor,
            'carbonclick_logo' => $this->getViewFileUrl('Carbonclick_CFC::images/'.$filename),
            'carbonweight' => $co2weight,
            'addtocarturl' => $this->getAddtocartUrl($product),
            'product_price' => $showdecimal ? $price : substr($price, 0, strpos($price, ".")),
            'impactalldata' => $impactalldata,
            'product_in_cart' => $cartitem ? true : false,
            'remove_url' => $cartitem ? $this->getRemoveCartUrl($cartitem) : false,
            'item_id' => $cartitem ? $cartitem->getId() : false,
            'removeitem_url' => $this->getRemoveItemUrl()
        ];
        return $additionalVariables;
    }

    public function ConvertToStoreWeight($weight, $unit)
    {
        $weightUnit = $this->getHelper()->getConfig("general/locale/weight_unit");
        if ($weightUnit == "kgs") {
            return [number_format($weight, 0),$unit];
        } elseif ($weightUnit == "lbs") {
            return [number_format($weight*2.20462262185, 0),$weightUnit];
        }
        return [number_format($weight, 0),$unit];
    }

    public function getRemoveItemUrl()
    {
        return $this->getUrl('checkout/sidebar/removeItem', ['_secure' => $this->getRequest()->isSecure()]);
    }
}
