<?php
class Magestore_Bannerslider_Block_Adminhtml_Report extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_report';
    $this->_blockGroup = 'bannerslider';	
    $this->_headerText = Mage::helper('bannerslider')->__('Report');
    parent::__construct();
	 $this->removeButton('add');
  }
}

