<?php

namespace Carbonclick\CFC\Model\Service;


class SaveDashboard extends AbstractService
{
    public function saveConfig($path,$value){
    	$this->configWriter->save($path,$value);
        $this->refreshCache();
        return $this;
    }
}
