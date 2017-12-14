<?php

class Chapagain_GoogleTagManager_Model_Gtm extends Mage_Core_Model_Abstract
{		
	/**
	 * Get product by sku
	 *
	 * @param Mage_Catalog_Model_Product
	 */
	public function getProductBySku($sku)
	{		
		$collection =  Mage::getModel('catalog/product')->loadByAttribute('sku',$sku);		
		return $collection;
	}		
	
}
