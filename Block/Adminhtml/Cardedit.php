<?php

namespace Carbonclick\CFC\Block\Adminhtml;

class Cardedit extends Dashboard
{

    public function getCarbonclickConfig()
    {
        return $this->fetchcustomer->getAPIConfig();
    }
}
