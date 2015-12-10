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
 * @package 	Magestore_Survey
 * @copyright 	Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license 	http://www.magestore.com/license-agreement.html
 */

/**
 * Survey Adminhtml Block
 * 
 * @category 	Magestore
 * @package 	Magestore_Survey
 * @author  	Magestore Developer
 */
class Magestore_Bannerslider_Block_Adminhtml_Date extends Mage_Core_Block_Template {

    public function _prepareLayout() {
        $this->setChild('refresh_button', $this->getLayout()->createBlock('adminhtml/widget_button')
                        ->setData(array(
                            'label' => Mage::helper('adminhtml')->__('Refresh'),
                            'onclick' => $this->getRefreshButtonCallback(),
                            'class' => 'task'
                        ))
        );
        parent::_prepareLayout();
        return $this;
    }

    public function getRefreshButtonHtml() {
        return $this->getChildHtml('refresh_button');
    }

    public function getDateFormat() {
        return $this->getLocale()->getDateStrFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
    }

    public function getLocale() {
        if (!$this->_locale) {
            $this->_locale = Mage::app()->getLocale();
        }
        return $this->_locale;
    }

    public function getFilter($nameFilter) {
		$date = array();			
        $filter = Mage::app()->getRequest()->getParam('filter');
		if($filter){
			$date = Mage::helper('adminhtml')->prepareFilterString($filter);
			if(isset($date[$nameFilter])) return $date[$nameFilter];			
		} 
		return null;
    }

}