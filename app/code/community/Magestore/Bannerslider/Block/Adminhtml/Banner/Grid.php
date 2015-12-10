<?php

class Magestore_Bannerslider_Block_Adminhtml_Banner_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('bannerGrid');
        $this->setDefaultSort('banner_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $storeId = $this->getRequest()->getParam('store');
        $collection = Mage::getModel('bannerslider/banner')->getCollection()->setStoreId($storeId);        
        
        $collection->getSelect()->joinLeft(array('table_alias' => $collection->getTable('bannerslider/bannerslider')), 'main_table.bannerslider_id = table_alias.bannerslider_id', array('bannerslider_title' => 'table_alias.title'));
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('banner_id', array(
            'header' => Mage::helper('bannerslider')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'filter_index' => 'main_table.banner_id',
            'index' => 'banner_id',
        ));

        $this->addColumn('name', array(
            'header' => Mage::helper('bannerslider')->__('Name'),
            'align' => 'left',
            'width' => '100px',
            'index' => 'name',
        ));

        $this->addColumn('click_url', array(
            'header' => Mage::helper('bannerslider')->__('URL'),
            'align' => 'left',
            'index' => 'click_url',
        ));

        $this->addColumn('bannerslider_title', array(
            'header' => Mage::helper('bannerslider')->__('Slider'),
            'align' => 'left',
            'filter_index' => 'table_alias.title',
            'index' => 'bannerslider_title',
        ));
//        $this->addColumn('banner_click', array(
//            'header' => Mage::helper('bannerslider')->__('Click Total'),
//            'align' => 'left',
//            'index' => 'banner_click',
//        ));
//
//        $this->addColumn('banner_impress', array(
//            'header' => Mage::helper('bannerslider')->__('Impression Total'),
//            'align' => 'left',
//            'index' => 'banner_impress',
//        ));
        $this->addColumn('start_time', array(
            'header' => Mage::helper('bannerslider')->__('Start Date'),
            'align' => 'left',
            'type' => 'datetime',
            'index' => 'start_time',
        ));

        $this->addColumn('end_time', array(
            'header' => Mage::helper('bannerslider')->__('End Date'),
            'align' => 'left',
            'type' => 'datetime',
            'index' => 'end_time',
        ));


        $this->addColumn('status', array(
            'header' => Mage::helper('bannerslider')->__('Status'),
            'align' => 'left',
            'width' => '80px',
            'filter_index' => 'main_table.status',
            'index' => 'status',
            'type' => 'options',
            'options' => array(
                0 => 'Enabled',
                1 => 'Disabled',
            ),
        ));

        $this->addColumn('action', array(
            'header' => Mage::helper('bannerslider')->__('Action'),
            'width' => '50px',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('bannerslider')->__('Edit'),
                    'url' => array('base' => '*/*/edit'),
                    'field' => 'id'
                )
            ),
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
            'is_system' => true,
        ));
        $this->addColumn('imagename', array(
            'header' => Mage::helper('bannerslider')->__('Image'),
            'align' => 'center',
            'width' => '70px',
            'index' => 'imagename',
            'renderer' => 'bannerslider/adminhtml_renderer_imagebanner'
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('bannerslider')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('bannerslider')->__('XML'));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('banner');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('bannerslider')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('bannerslider')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('bannerslider/status')->getOptionArray();

        array_unshift($statuses, array('label' => '', 'value' => ''));
        $this->getMassactionBlock()->addItem('status', array(
            'label' => Mage::helper('bannerslider')->__('Change status'),
            'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('bannerslider')->__('Status'),
                    'values' => array(
                        0 => 'Enabled',
                        1 => 'Disabled',
                    )
                )
            )
        ));
        return $this;
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getId(), 'store' => $this->getRequest()->getParam('store')));
    }

}