<?php

namespace Carbonclick\CFC\Block\Adminhtml;

class Reward extends Dashboard
{
    public function getConfig($path){
        return $this->merchantImpact->getConfig($path);
    }
}
