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
class Magestore_Bannerslider_Block_Adminhtml_Report_Banner_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('reportGrid');
        $this->setDefaultSort('report_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * prepare collection for block to display
     *
     * @return Magestore_Bannerslider_Block_Adminhtml_Bannerslider_Grid
     */
    protected function _prepareCollection() {
        $filter = Mage::app()->getRequest()->getParam('filter');
        $date = Mage::helper('adminhtml')->prepareFilterString($filter);
        $collection = Mage::getModel('bannerslider/report')->getCollection();
        if (isset($date['report_from']) && isset($date['report_to'])) {
            $collection->addFieldtoFilter('date_click', array('from' => $date['report_from'],
                'to' => $date['report_to'],
                'date' => true,));
        }
        $collection->getSelect()->joinLeft(array('table_banner' => $collection->getTable('bannerslider/banner')), 'main_table.banner_id = table_banner.banner_id', array('banner_name' => 'table_banner.name', 'banner_url' => 'table_banner.click_url'));
        $collection->getSelect()
                ->columns('SUM(main_table.clicks) AS banner_click')
                ->columns('SUM(main_table.impmode) AS banner_impress')
                ->group('main_table.banner_id');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare columns for this grid
     *
     * @return Magestore_Bannerslider_Block_Adminhtml_Bannerslider_Grid
     */
    protected function _prepareColumns() {
        $this->addColumn('report_id', array(
            'header' => Mage::helper('bannerslider')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'report_id',
        ));

        $this->addColumn('banner_name', array(
            'header' => Mage::helper('bannerslider')->__('Banner'),
            'align' => 'left',
            'filter_index' => 'table_banner.name',
            'index' => 'banner_name',
        ));

        $this->addColumn('banner_url', array(
            'header' => Mage::helper('bannerslider')->__('URL'),
            'align' => 'left',
            'filter_index' => 'table_banner.click_url',
            'index' => 'banner_url',
        ));

        $this->addColumn('banner_click', array(
            'header' => Mage::helper('bannerslider')->__('Clicks'),
            'align' => 'left',
            'index' => 'banner_click',
            'filter_index' => 'main_table.clicks',
            'type' => 'number',
            'width' => '200px',
        ));

        $this->addColumn('banner_impress', array(
            'header' => Mage::helper('bannerslider')->__('Impressions'),
            'align' => 'left',
            'index' => 'banner_impress',
            'filter_index' => 'main_table.impmode',
            'type' => 'number',
            'width' => '200px',
        ));
        $this->addColumn('imagename', array(
            'header' => Mage::helper('bannerslider')->__('Image'),
            'align' => 'center',
            'width' => '70px',
            'index' => 'imagename',
            'renderer' => 'bannerslider/adminhtml_renderer_imagereport'
        ));

        $this->addExportType('*/*/exportCsvBanner', Mage::helper('bannerslider')->__('CSV'));
        $this->addExportType('*/*/exportXmlBanner', Mage::helper('bannerslider')->__('XML'));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row) {
        return '';
    }

    public function getCsv() {
        $csv = '';
        $this->_isExport = true;
        $this->_prepareGrid();
        $this->getCollection()->getSelect()->limit();
        $this->getCollection()->setPageSize(0);
        $this->getCollection()->load();
        $this->_afterLoadCollection();

        $data = array();
        $data[] = '"' . Mage::helper('bannerslider')->__('ID') . '"';
        $data[] = '"' . Mage::helper('bannerslider')->__('Banner') . '"';
        $data[] = '"' . Mage::helper('bannerslider')->__('URL') . '"';
        $data[] = '"' . Mage::helper('bannerslider')->__('Clicks') . '"';
        $data[] = '"' . Mage::helper('bannerslider')->__('Impression') . '"';
        $data[] = '"' . Mage::helper('bannerslider')->__('Date') . '"';
        $csv.= implode(',', $data) . "\n";

        foreach ($this->getCollection() as $item) {
            $data = Mage::helper('bannerslider')->getValueToCsv($item, 1);
            $csv.= $data . "\n";
        }
        return $csv;
    }

    public function getXml() {
        $this->_isExport = true;
        $this->_prepareGrid();
        $this->getCollection()->getSelect()->limit();
        $this->getCollection()->setPageSize(0);
        $this->getCollection()->load();
        $this->_afterLoadCollection();
        $indexes = array();
        foreach ($this->_columns as $column) {
            if (!$column->getIsSystem()) {
                $indexes[] = $column->getIndex();
            }
        }
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml.= '<items>';
        foreach ($this->getCollection() as $item) {
            $xml .= '<item>';
            foreach ($this->_columns as $column) {
                if (!$column->getIsSystem()) {
                    if ($column->getIndex() == 'imagename' || $column->getIndex() == 'slider_title')
                        continue;
                    $xml .= "<" . $column->getIndex() . "><![CDATA[";
                    $xml .= Mage::helper('bannerslider')->getValueToXml($column->getIndex(), $item);
                    $xml .= "]]></" . $column->getIndex() . ">";
                }
            }
            $xml .= '</item>';
        }
        $xml.= '</items>';
        return $xml;
    }

}