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
class Magestore_Bannerslider_Block_Adminhtml_Bannerslider_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct(){
		parent::__construct();
		$this->setId('bannersliderGrid');
		$this->setDefaultSort('bannerslider_id');
		$this->setDefaultDir('ASC');
		$this->setSaveParametersInSession(true);
	}
	
	/**
	 * prepare collection for block to display
	 *
	 * @return Magestore_Bannerslider_Block_Adminhtml_Bannerslider_Grid
	 */
	protected function _prepareCollection(){
		$collection = Mage::getModel('bannerslider/bannerslider')->getCollection();
                //Zend_debug::dump($collection);die();
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}
	
	/**
	 * prepare columns for this grid
	 *
	 * @return Magestore_Bannerslider_Block_Adminhtml_Bannerslider_Grid
	 */
	protected function _prepareColumns(){
		$this->addColumn('bannerslider_id', array(
			'header'	=> Mage::helper('bannerslider')->__('ID'),
			'align'	 =>'right',
			'width'	 => '50px',
			'index'	 => 'bannerslider_id',
		));

		$this->addColumn('title', array(
			'header'	=> Mage::helper('bannerslider')->__('Title'),
			'align'	 =>'left',
			'index'	 => 'title',
		));
                                
		 $this->addColumn('position', array(
			'header'	=> Mage::helper('bannerslider')->__('Position'),
			'align'	 =>'left',
			'index'	 => 'position',
                        'type'		=> 'options',
                        'options' => Mage::helper('bannerslider')->getOptionGridSlider()

		));
                
                $this->addColumn('style_content', array(
			'header'	=> Mage::helper('bannerslider')->__('Slider\'s Mode'),
			'width'	 => '150px',
			'index'	 => 'style_content',
                        'type'		=> 'options',
                        'options' => array(
				0 => 'Standard Slider',
				1 => 'Custom Slider',
			),

		));
                
		$this->addColumn('status', array(
			'header'	=> Mage::helper('bannerslider')->__('Status'),
			'align'	 => 'left',
			'width'	 => '80px',
			'index'	 => 'status',
			'type'		=> 'options',
			'options'	 => array(
				0 => 'Enabled',
				1 => 'Disabled',
			),
		));

		$this->addColumn('action',
			array(
				'header'	=>	Mage::helper('bannerslider')->__('Action'),
				'width'		=> '100',
				'type'		=> 'action',
				'getter'	=> 'getId',
				'actions'	=> array(
					array(
						'caption'	=> Mage::helper('bannerslider')->__('Edit'),
						'url'		=> array('base'=> '*/*/edit'),
						'field'		=> 'id'
					)),
				'filter'	=> false,
				'sortable'	=> false,
				'index'		=> 'stores',
				'is_system'	=> true,
		));

		$this->addExportType('*/*/exportCsv', Mage::helper('bannerslider')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('bannerslider')->__('XML'));

		return parent::_prepareColumns();
	}
	
	/**
	 * prepare mass action for this grid
	 *
	 * @return Magestore_Bannerslider_Block_Adminhtml_Bannerslider_Grid
	 */
	protected function _prepareMassaction(){
		$this->setMassactionIdField('bannerslider_id');
		$this->getMassactionBlock()->setFormFieldName('bannerslider');

		$this->getMassactionBlock()->addItem('delete', array(
			'label'		=> Mage::helper('bannerslider')->__('Delete'),
			'url'		=> $this->getUrl('*/*/massDelete'),
			'confirm'	=> Mage::helper('bannerslider')->__('Are you sure?')
		));

		$statuses = Mage::getSingleton('bannerslider/status')->getOptionArray();                
		array_unshift($statuses, array('label'=>'', 'value'=>''));
		$this->getMassactionBlock()->addItem('status', array(
			'label'=> Mage::helper('bannerslider')->__('Change status'),
			'url'	=> $this->getUrl('*/*/massStatus', array('_current'=>true)),
			'additional' => array(
				'visibility' => array(
					'name'	=> 'status',
					'type'	=> 'select',
					'class'	=> 'required-entry',
					'label'	=> Mage::helper('bannerslider')->__('Status'),
					'values'=> array(
                                            0 => 'Enabled',
                                            1 => 'Disabled',
                                        )
				))
		));
		return $this;
	}
	
	/**
	 * get url for each row in grid
	 *
	 * @return string
	 */
	public function getRowUrl($row){
		return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}
}