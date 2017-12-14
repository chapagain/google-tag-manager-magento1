<?php

class Chapagain_GoogleTagManager_Helper_Data extends Mage_Core_Helper_Abstract
{
	const CONFIG_GTM_GENERAL_IS_ENABLE = 'googletagmanager/general/is_enable';
	const CONFIG_GTM_GENERAL_CONTAINER_ID = 'googletagmanager/general/container_id';
	const CONFIG_GTM_GENERAL_IS_ENABLE_DATA_LAYER = 'googletagmanager/general/is_enable_data_layer';
		
	public function getContainerId()
    {
        return Mage::getStoreConfig(self::CONFIG_GTM_GENERAL_CONTAINER_ID);        
    }
    
    public function getIsEnable()
    {
        return Mage::getStoreConfig(self::CONFIG_GTM_GENERAL_IS_ENABLE);        
    }
    
    public function getIsEnableDataLayer()
    {		
        return Mage::getStoreConfig(self::CONFIG_GTM_GENERAL_IS_ENABLE_DATA_LAYER);
    }
    
    /**
     * Checks if current page is product view page
     * 
     * @return Mage_Catalog_Model_Product|false 
     */ 
    public function checkCurrentProduct()
	{
		$product = Mage::registry('current_product');		
		if (!$product) {
			return false;
		} 		
		return $product;
	}
    
    /**
     * Checks if customer is login
     * Returns customer ID if customer is logged in
     * Else returns false
     * 
     * @return integer|false 
     */ 
    public function checkCustomerLogin()
    {
		$customerId = Mage::getSingleton('customer/session')->getCustomer()->getId();
		if (!$customerId) {
			return false;
		} 		
		return $customerId;
	}	
	
}
