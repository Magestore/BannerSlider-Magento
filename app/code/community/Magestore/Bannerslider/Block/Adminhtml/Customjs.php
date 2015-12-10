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
class Magestore_Bannerslider_Block_Adminhtml_Customjs extends Mage_Core_Block_Template {

    public function _prepareLayout() {

        parent::_prepareLayout();
        return $this;
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

}