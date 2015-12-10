<?php

class Magestore_Bannerslider_Block_Adminhtml_Banner_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'bannerslider';
        $this->_controller = 'adminhtml_banner';
        
        if(Mage::app()->getRequest()->getActionName()!='addin'){
			$this->_updateButton('save', 'label', Mage::helper('bannerslider')->__('Save Banner'));
			$this->_updateButton('delete', 'label', Mage::helper('bannerslider')->__('Delete Banner'));
			
			$this->_addButton('saveandcontinue', array(
				'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
				'onclick'   => 'saveAndContinueEdit()',
				'class'     => 'save',
			), -100);

			$this->_formScripts[] = "
				
				function saveAndContinueEdit(){
					editForm.submit($('edit_form').action+'back/edit/');
				}
			";
		}else{
			 $this->_removeButton('save', 'label', Mage::helper('bannerslider')->__('Save Banner'));
			$this->_removeButton('delete', 'label', Mage::helper('bannerslider')->__('Delete Banner'));
			 $this->_removeButton('reset', 'label', Mage::helper('bannerslider')->__('Delete Banner'));
			$this->_removeButton('back', 'label', Mage::helper('bannerslider')->__('Delete Banner'));
			
			$this->_addButton('saveandcontinue', array(
				'label'     => Mage::helper('adminhtml')->__('Save'),
				'onclick'   => 'saveAndContinueEdit()',
				'class'     => 'save',
			), -100);
			$this->_addButton('close', array(
				'label'     => Mage::helper('adminhtml')->__('Close'),
				'onclick'   => 'closeWindow()',
				'class'     => 'save',
			), -100);

			$this->_formScripts[] = "
				
				function saveAndContinueEdit(){
					editForm.submit($('edit_form').action+'addin/edit/');
				}
				function closeWindow(){
					window.opener.location.href = window.opener.location.href+'active_tab/custom/';
					if (window.opener.progressWindow){
						window.opener.progressWindow.close()
					}
					window.close();
				}
			";
		}
    }

    public function getHeaderText()
    {
        if( Mage::registry('banner_data') && Mage::registry('banner_data')->getId() ) {
            return Mage::helper('bannerslider')->__("Edit Banner '%s'", $this->htmlEscape(Mage::registry('banner_data')->getName()));
        } else {
            return Mage::helper('bannerslider')->__('Add Banner');
        }
    }
}