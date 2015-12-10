<?php 
class Magestore_Bannerslider_Block_Adminhtml_Renderer_Edit
	extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	/* Render Grid Column*/
	public function render(Varien_Object $row) 
	{       		
		return sprintf('
			<a href="javascript:void(0);" onclick="%s">%s</a>',
			"window.open('".Mage::getSingleton('adminhtml/url')->getUrl('*/bannerslider_banner/addin', array( 'sliderid' => $this->getRequest()->getParam('id'),'id' => $row->getId()))."','Gamekings','width=1024,height=650')",
			Mage::helper('bannerslider')->__('Edit')
		);
	}
}