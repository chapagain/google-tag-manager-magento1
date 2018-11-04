<?php 
 
class Chapagain_GoogleTagManager_Block_Gtm extends Mage_Core_Block_Template
{			
	public function getIsHomePage() 
	{
		$routeName = Mage::app()->getRequest()->getRouteName();
		$identifier = Mage::getSingleton('cms/page')->getIdentifier();	  

		if($routeName == 'cms' && $identifier == 'home') {
			return true;
		} else {
			return false;
		}
	}
	
	public function getIsOrderSuccessPage()
	{
		if (strpos(Mage::app()->getRequest()->getPathInfo(), '/checkout/onepage/success') !== false) {
			return true;
		}
		return false;
	}
	
	public function getIsCartPage() 
	{
		if (strpos(Mage::app()->getRequest()->getPathInfo(), '/checkout/cart') !== false) {
			return true;
		}
		return false;
	}
	
	public function getIsCheckoutPage()
	{
		if (strpos(Mage::app()->getRequest()->getPathInfo(), '/checkout/onepage') !== false) {
			return true;
		}
		return false;
	}
	
	public function getCurrentProduct()
	{	
		return Mage::registry('current_product');		
	}
	
	public function getCurrentCategory()
	{	
		return Mage::registry('current_category');		
	}
	
	public function getOrder()
    {   	
		if ($this->getIsOrderSuccessPage()) {
			$orderId = Mage::getSingleton('checkout/session')->getLastOrderId();		
			$order = Mage::getModel('sales/order')->load($orderId);
			if (!$order) {
				return false;
			}			
			return $order;
		}
		return false;
    }
    
    public function getDataLayerProduct()
    {
		if ($product = $this->getCurrentProduct()) {			
			
			$categoryCollection = $product->getCategoryCollection();
			
			$categories = array();
			foreach ($categoryCollection as $category) {
				$categories[] = Mage::getModel('catalog/category')->load($category->getEntityId())->getName();
			}			
			
			$objProduct = new stdClass();
			$objProduct->name = $product->getName();
			$objProduct->id = $product->getSku();
			//$objProduct->price = Mage::getModel('directory/currency')->formatTxt($product->getPrice(), array('display' => Zend_Currency::NO_SYMBOL));
			
			//$objProduct->price = Mage::getModel('directory/currency')->formatTxt(Mage::helper('tax')->getPrice($product, $product->getFinalPrice(), true), array('display' => Zend_Currency::NO_SYMBOL));
			
			/** 
			 * Fix for comma separated price when price is 1000 or more
			 * it becomes like 1,230 or 1,235.55 
			 * so, removing commas from price
			 * which make the price 1230 or 1235.55
			 */
			//$objProduct->price = str_replace(',', '', Mage::getModel('directory/currency')->formatTxt(Mage::helper('tax')->getPrice($product, $product->getFinalPrice(), true), array('display' => Zend_Currency::NO_SYMBOL)));
			
			/**
			 * Fix for another issue which came up after the above fix
			 * 
			 * Fix for comma separated price for different locale currencies
			 * System -> Configuration -> General -> Locale Options
			 * This menu has the option to choose different countries locale.
			 * 
			 * By default, it's the English (United States) and the above fix of removing commas from price works fine with English locale but there's problem with other locale like Dutch locale where price is comma separated instead of decimal separated.
			 * 
			 * Price display of Dutch locale is the opposite of English locale
			 * English locale price = 1,230.55 becomes the following in Dutch locale:
			 * Dutch locale price = 1.230,55
			 */
			$objProduct->price = (string) Mage::app()->getLocale()->getNumber(Mage::helper('tax')->getPrice($product, $product->getFinalPrice(), true));
			
			$objProduct->category = implode('|', $categories);
			
			$objEcommerce = new stdClass();			
			$objEcommerce->ecommerce = new stdClass();
			$objEcommerce->ecommerce->detail = new stdClass();
			$objEcommerce->ecommerce->detail->actionField = new stdClass();
			$objEcommerce->ecommerce->detail->actionField->list = 'Catalog';
			$objEcommerce->ecommerce->detail->products = $objProduct;
									
			/*$objAddToCart = new stdClass();
			$objAddToCart->event = 'addToCart';
			$objAddToCart->ecommerce = new stdClass();
			$objAddToCart->ecommerce->add = new stdClass();
			$objAddToCart->ecommerce->add->products = $objProduct;*/
									
			$pageCategory = json_encode(array('pageCategory' => 'product-detail'), JSON_PRETTY_PRINT);
			
			$dataScript = PHP_EOL;
			
			$dataScript .= '<script type="text/javascript">'.PHP_EOL.'dataLayer = [' . $pageCategory . '];'.PHP_EOL.'</script>';
			
			$dataScript .= PHP_EOL.PHP_EOL;
			
			$dataScript .= '<script type="text/javascript">'.PHP_EOL.'dataLayer.push('. json_encode($objEcommerce, JSON_PRETTY_PRINT) . ');'.PHP_EOL.'</script>';
			
			//$dataScript .= PHP_EOL.PHP_EOL;
			
			//$dataScript .= '<script type="text/javascript">'.PHP_EOL.'dataLayer.push('. json_encode($objAddToCart, JSON_PRETTY_PRINT) . ');'.PHP_EOL.'</script>';
			
			return $dataScript;
		}
	}
	
	public function getDataLayerOrder()
	{
		if ($order = $this->getOrder()) {
									
			$aItems = array();
			$productItems = array();			
			foreach ($order->getAllVisibleItems() as $item) {
				
				$categoryCollection = $item->getProduct()->getCategoryCollection();
				$categories = array();
				foreach ($categoryCollection as $category) {
					$categories[] = Mage::getModel('catalog/category')->load($category->getEntityId())->getName();
				}
				
				$productItem = array();
				$productItem['name'] = $item->getName();			  
				$productItem['id'] = $item->getSku();
				
				//$productItem['price'] = Mage::getModel('directory/currency')->formatTxt($item->getBasePrice(), array('display' => Zend_Currency::NO_SYMBOL));				
				//$productItem['price'] = str_replace(',', '', Mage::getModel('directory/currency')->formatTxt($item->getBasePrice(), array('display' => Zend_Currency::NO_SYMBOL)));
				$productItem['price'] = (string) Mage::app()->getLocale()->getNumber($item->getBasePrice());
				
				$productItem['category'] = implode('|', $categories);
				$productItem['quantity'] = intval($item->getQtyOrdered()); // converting qty from decimal to integer
				$productItem['coupon'] = '';
				$productItems[] = (object) $productItem;

				$objItem = array();
				$objItem['sku'] = $item->getSku();
				$objItem['name'] = $item->getName();
				$objItem['category'] = implode('|', $categories);
				
				//$objItem['price'] = Mage::getModel('directory/currency')->formatTxt($item->getBasePrice(), array('display' => Zend_Currency::NO_SYMBOL));
				//$objItem['price'] = str_replace(',', '', Mage::getModel('directory/currency')->formatTxt($item->getBasePrice(), array('display' => Zend_Currency::NO_SYMBOL)));
				$objItem['price'] = (string) Mage::app()->getLocale()->getNumber($item->getBasePrice());
				
				$objItem['quantity'] = intval($item->getQtyOrdered()); // converting qty from decimal to integer
				$aItems[] = (object) $objItem;
			}
			
			$objOrder = new stdClass();
			
			$objOrder->transactionId = $order->getIncrementId();
			$objOrder->transactionAffiliation = Mage::app()->getStore()->getFrontendName();
			
			//$objOrder->transactionTotal = Mage::getModel('directory/currency')->formatTxt($order->getBaseGrandTotal(), array('display' => Zend_Currency::NO_SYMBOL));
			//$objOrder->transactionTax = Mage::getModel('directory/currency')->formatTxt($order->getBaseTaxAmount(), array('display' => Zend_Currency::NO_SYMBOL));
			//$objOrder->transactionShipping = Mage::getModel('directory/currency')->formatTxt($order->getBaseShippingAmount(), array('display' => Zend_Currency::NO_SYMBOL));
			
			//$objOrder->transactionTotal = str_replace(',', '', Mage::getModel('directory/currency')->formatTxt($order->getBaseGrandTotal(), array('display' => Zend_Currency::NO_SYMBOL)));
			//$objOrder->transactionTax = str_replace(',', '', Mage::getModel('directory/currency')->formatTxt($order->getBaseTaxAmount(), array('display' => Zend_Currency::NO_SYMBOL)));
			//$objOrder->transactionShipping = str_replace(',', '', Mage::getModel('directory/currency')->formatTxt($order->getBaseShippingAmount(), array('display' => Zend_Currency::NO_SYMBOL)));

			$objOrder->transactionTotal = (float) Mage::app()->getLocale()->getNumber($order->getBaseGrandTotal());
			$objOrder->transactionTax = (float) Mage::app()->getLocale()->getNumber($order->getBaseTaxAmount());
			$objOrder->transactionShipping = (float) Mage::app()->getLocale()->getNumber($order->getBaseShippingAmount());


			$objOrder->transactionProducts = $aItems;
						
			$objOrder->ecommerce = new stdClass();
			$objOrder->ecommerce->purchase = new stdClass();
			$objOrder->ecommerce->purchase->actionField = new stdClass();
			$objOrder->ecommerce->purchase->actionField->id = $order->getIncrementId();
			$objOrder->ecommerce->purchase->actionField->affiliation = Mage::app()->getStore()->getFrontendName();
			
			//$objOrder->ecommerce->purchase->actionField->revenue = Mage::getModel('directory/currency')->formatTxt($order->getBaseGrandTotal(), array('display' => Zend_Currency::NO_SYMBOL));
			//$objOrder->ecommerce->purchase->actionField->tax = Mage::getModel('directory/currency')->formatTxt($order->getBaseTaxAmount(), array('display' => Zend_Currency::NO_SYMBOL));
			//$objOrder->ecommerce->purchase->actionField->shipping = Mage::getModel('directory/currency')->formatTxt($order->getBaseShippingAmount(), array('display' => Zend_Currency::NO_SYMBOL));
			
			//$objOrder->ecommerce->purchase->actionField->revenue = str_replace(',', '', Mage::getModel('directory/currency')->formatTxt($order->getBaseGrandTotal(), array('display' => Zend_Currency::NO_SYMBOL)));
			//$objOrder->ecommerce->purchase->actionField->tax = str_replace(',', '', Mage::getModel('directory/currency')->formatTxt($order->getBaseTaxAmount(), array('display' => Zend_Currency::NO_SYMBOL)));
			//$objOrder->ecommerce->purchase->actionField->shipping = str_replace(',', '', Mage::getModel('directory/currency')->formatTxt($order->getBaseShippingAmount(), array('display' => Zend_Currency::NO_SYMBOL)));

			$objOrder->ecommerce->purchase->actionField->revenue = (float) Mage::app()->getLocale()->getNumber($order->getBaseGrandTotal());
			$objOrder->ecommerce->purchase->actionField->tax = (float) Mage::app()->getLocale()->getNumber($order->getBaseTaxAmount());
			$objOrder->ecommerce->purchase->actionField->shipping = (float) Mage::app()->getLocale()->getNumber($order->getBaseShippingAmount());
			
			
			$coupon = $order->getCouponCode();
			$objOrder->ecommerce->purchase->actionField->coupon = $coupon == null ? '' : $coupon;
			
			$objOrder->ecommerce->products = $productItems;
						
			$pageCategory = json_encode(array('pageCategory' => 'order-success'), JSON_PRETTY_PRINT);
			
			$dataScript = PHP_EOL;
			
			$dataScript .= '<script type="text/javascript">'.PHP_EOL.'dataLayer = [' . $pageCategory . '];'.PHP_EOL.'</script>';
			
			$dataScript .= PHP_EOL.PHP_EOL;
			
			$dataScript .= '<script type="text/javascript">'.PHP_EOL.'dataLayer.push('. json_encode($objOrder, JSON_PRETTY_PRINT) . ');'.PHP_EOL.'</script>';		
			
			return $dataScript;		
		}
	}
}
