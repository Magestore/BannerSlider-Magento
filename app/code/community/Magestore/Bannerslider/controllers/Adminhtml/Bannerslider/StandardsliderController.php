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
 * @package 	Magestore_Bannerslider
 * @copyright 	Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license 	http://www.magestore.com/license-agreement.html
 */

 /**
 * Bannerslider Adminhtml Controller
 * 
 * @category 	Magestore
 * @package 	Magestore_Bannerslider
 * @author  	Magestore Developer
 */
class Magestore_Bannerslider_Adminhtml_Bannerslider_StandardsliderController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction(){
		$this->loadLayout()
			->_setActiveMenu('bannerslider/standslider')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		return $this;
	}
 
	/**
	 * index action
	 */
	public function indexAction(){
            
		$this->_initAction()
			->renderLayout();
	}
        
        public function previewAction(){
            $this->loadLayout(false)
                    ->renderLayout();
        }
    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('bannerslider');
    }
}