<?php

namespace Carbonclick\CFC\Model;

use Carbonclick\CFC\Api\PaymentfailedInterface;


class Paymentfailed extends CreateProduct implements PaymentfailedInterface
{
	public function update($type,$message)
	{
		$shop = $this->updateshop->getShop();
		$token = $this->getBearerToken();
		if($shop['access_token'] == $token){
			switch ($type) {
		  	case "charge_failed":
		    	$this->AddfailedMessage("payment",$message);
		    	return true;
		    	break;
		  	case "charge_succeeded":
		    	$this->removefailedMessage("payment");
		    	return true;
		    	break;
		    case "invoice_payment_succeeded":
		    	$this->removefailedMessage("invoice");
		    	return true;
		    	break;
		    case "invoice_payment_failed":
		    	$this->AddfailedMessage("invoice",$message);
		    	return true;
		    	break;
		    case "blocked":
		    	$this->BlockUser();
		    	return true;
		    	break;
		    case "unblocked":
		    	$this->UnblockUser();
		    	return true;
		    	break;
			}
		}

		return false;
	}

	private function getBearerToken() {
	    $headers = getallheaders();
	    // HEADER: Get the access token from the header
	    if (!empty($headers['Authorization'])) {
	        if (preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
	            return $matches[1];
	        }
	    }
    	return false;
	}

	public function AddfailedMessage($type, $message){
		$this->saveconfig->saveConfig('cfc/general/'.$type,$message);
        return $this;
	}

	public function removefailedMessage($type){
		$this->saveconfig->saveConfig('cfc/general/'.$type,"");
        return $this;
	}

	public function BlockUser(){
		$this->saveconfig->saveConfig('cfc/general/enable',0);
        $this->UpdateStatus(0);
       	$this->updateshop->UpdateShop(['setup'=>false]);
        return $this;
	}

	public function UnblockUser(){
		$this->saveconfig->saveConfig('cfc/general/enable',1);
        $this->UpdateStatus(1);
       	$this->updateshop->UpdateShop(['setup'=>true]);
        return $this;
	}
}
