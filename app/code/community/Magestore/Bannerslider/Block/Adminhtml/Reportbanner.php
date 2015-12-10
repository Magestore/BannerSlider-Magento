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
class Magestore_Bannerslider_Block_Adminhtml_Reportbanner extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        $this->_controller = 'adminhtml_report_banner';
        $this->_blockGroup = 'bannerslider';
        $this->_headerText = Mage::helper('bannerslider')->__('Report');
        //$this->_addButtonLabel = Mage::helper('survey')->__('Add Item');
        parent::__construct();
        $this->_removeButton('add');
    }

}