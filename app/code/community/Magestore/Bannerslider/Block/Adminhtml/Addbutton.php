<?php

/**
 * Magestore
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category 	Magestore
 * @package 	Magestore_Storelocator
 * @copyright 	Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license 	http://www.magestore.com/license-agreement.html
 */

/**
 * Storelocator Block
 * 
 * @category 	Magestore
 * @package 	Magestore_Bannerslider
 * @author  	Magestore Developer
 */
class Magestore_Bannerslider_Block_Adminhtml_Addbutton extends Mage_Core_Block_Template {

    /**
     * prepare block's layout
     *
     * @return Magestore_Bannerslider_Block_Adminhtml_Addbutton
     */
    public function _prepareLayout() {
        return parent::_prepareLayout();
    }
    
    public function getUrlAddBanner(){                        
        $url = Mage::getSingleton('adminhtml/url')->getUrl('*/bannerslider_banner/addin');
        return $url.'sliderid/'.$this->getRequest()->getParam('id');
    }
    
    public function getBanner($id){
        return Mage::getModel('bannerslider/banner')->load($id);
    }
}