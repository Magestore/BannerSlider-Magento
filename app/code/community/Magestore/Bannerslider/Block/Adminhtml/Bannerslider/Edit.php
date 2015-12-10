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
 * Bannerslider Edit Block
 * 
 * @category 	Magestore
 * @package 	Magestore_Bannerslider
 * @author  	Magestore Developer
 */
class Magestore_Bannerslider_Block_Adminhtml_Bannerslider_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
	public function __construct(){
		parent::__construct();
		
		$this->_objectId = 'id';
		$this->_blockGroup = 'bannerslider';
		$this->_controller = 'adminhtml_bannerslider';
		
		$this->_updateButton('save', 'label', Mage::helper('bannerslider')->__('Save Slider'));
		$this->_updateButton('delete', 'label', Mage::helper('bannerslider')->__('Delete Slider'));
		
		$this->_addButton('saveandcontinue', array(
			'label'		=> Mage::helper('adminhtml')->__('Save And Continue Edit'),
			'onclick'	=> 'saveAndContinueEdit()',
			'class'		=> 'save',
		), -100);

		$this->_formScripts[] = "
			function toggleEditor() {
				if (tinyMCE.getInstanceById('bannerslider_content') == null)
					tinyMCE.execCommand('mceAddControl', false, 'bannerslider_content');
				else
					tinyMCE.execCommand('mceRemoveControl', false, 'bannerslider_content');
			}

			function saveAndContinueEdit(){
				editForm.submit($('edit_form').action+'back/edit/');
			}
		";
	}
	
	/**
	 * get text to show in header when edit an item
	 *
	 * @return string
	 */
	public function getHeaderText(){
		if(Mage::registry('bannerslider_data') && Mage::registry('bannerslider_data')->getId())
			return Mage::helper('bannerslider')->__("Edit Slider '%s'", $this->htmlEscape(Mage::registry('bannerslider_data')->getTitle()));
		return Mage::helper('bannerslider')->__('Add Slider');
	}
}