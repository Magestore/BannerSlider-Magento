<?php
class Magestore_Bannerslider_Block_Adminhtml_Banner extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_banner';
    $this->_blockGroup = 'bannerslider';
    $this->_headerText = Mage::helper('bannerslider')->__('Banner Manager');
    $this->_addButtonLabel = Mage::helper('bannerslider')->__('Add Banner');
    parent::__construct();
  }
}