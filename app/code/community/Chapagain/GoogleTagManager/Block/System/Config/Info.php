<?php
/**
 * @category   Chapagain
 * @package    Chapagain_GoogleTagManager
 * @author     mukesh.chapagain@gmail.com
 * @website    http://blog.chapagain.com.np
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Chapagain_GoogleTagManager_Block_System_Config_Info
    extends Mage_Adminhtml_Block_Abstract
    implements Varien_Data_Form_Element_Renderer_Interface
{

    /**
     * Render fieldset html
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
         $html = '<div>
                    <h4>About the Developer</h4>
                    <p>
                    <strong>Mukesh Chapagain</strong> <br />
                    Web developer, Programmer, Blogger, Engineer <br />
                    Freelancer with specialization in Magento eCommerce<br />
                    <br />
                    <a href="http://blog.chapagain.com.np" target="_blank">Blog</a> | 
                    <a href="mailto:mukesh.chapagain@gmail.com">Email</a> | 
                    <a href="http://linkedin.com/in/chapagain" target="_blank">LinkedIn</a> | 
                    <a href="http://github.com/chapagain" target="_blank">GitHub</a> | 
                    <a href="http://twitter.com/chapagain" target="_blank">Twitter</a> | 
                    <a href="http://facebook.com/mukesh.chapagain" target="_blank">Facebook</a> 
                    <br />
                    More extensions by the developer at <a href="https://marketplace.magento.com/partner/chapagain" target="_blank">Magento Marketplace</a>
                    </p>
                </div>';

        return $html;
    }
}
