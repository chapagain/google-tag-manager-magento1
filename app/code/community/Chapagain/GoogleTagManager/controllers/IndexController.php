<?php

class Chapagain_GoogleTagManager_IndexController extends Mage_Core_Controller_Front_Action
{	
	/**
	 * Get customer session data
	 */
	public function getCustomerSession()
	{
		return Mage::getSingleton('customer/session');
	}
	
	/**
	 * Get core session data
	 */
	public function getCoreSession()
	{
		return Mage::getSingleton('core/session');
	}

	
}
