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
 * Bannerslider Grid Block
 * 
 * @category 	Magestore
 * @package 	Magestore_Bannerslider
 * @author  	Magestore Developer
 */
class Magestore_Bannerslider_Block_Adminhtml_Standardslider_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct(){
		parent::__construct();
		$this->setId('standardsliderGrid');
		$this->setDefaultSort('standardslider_id');
		$this->setDefaultDir('ASC');
		$this->setSaveParametersInSession(true);
                $this->setFilterVisibility(false);
	}
	
	/**
	 * prepare collection for block to display
	 *
	 * @return Magestore_Bannerslider_Block_Adminhtml_Bannerslider_Grid
	 */
	protected function _prepareCollection(){
		$collection = Mage::helper('bannerslider')->getStandardSlider();
		$collectionGrid = new Varien_Data_Collection();
		foreach ($collection as $value) {
			$rowObject = new Varien_Object();
			$rowObject->setData('id', $value['value']);
			$rowObject->setData('title',$value['label']);
			$collectionGrid->addItem($rowObject);

		}
		$this->setCollection($collectionGrid);
		return parent::_prepareCollection();
	}
	
	/**
	 * prepare columns for this grid
	 *
	 * @return Magestore_Bannerslider_Block_Adminhtml_Bannerslider_Grid
	 */
	protected function _prepareColumns(){
		$this->addColumn('title', array(
			'header'	=> Mage::helper('bannerslider')->__('List Slider'),
			'align'	 =>'left',
			'index'	 => 'title',

		));
		$this->addColumn('action',
			array(
				'header'	=>	Mage::helper('bannerslider')->__('Preview'),
				'width'		=> '100',
				'type'		=> 'action',
				'getter'	=> 'getId',
				'actions'	=> array(
					array(
						'caption'	=> Mage::helper('bannerslider')->__('Preview'),
						'url'		=> array('base'=> '*/*/preview'),
                                                //'url'           => 'javascript:window.open('.$this->getUrl('*/*/preview').',_blank,scrollbars=yes, resizable=yes, width=800, height=700)',
                                                'popup'         => true,
						'field'		=> 'id'
					)),

				'is_system'	=> true,
		));
		return parent::_prepareColumns();
	}
	public function getRowUrl($row){
		return $this->getUrl('*/*/preview', array('id' => $row->getId()));
                
	}
}
