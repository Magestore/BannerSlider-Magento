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
 * Bannerslider Adminhtml Controller
 * 
 * @category 	Magestore
 * @package 	Magestore_Bannerslider
 * @author  	Magestore Developer
 */
class Magestore_Bannerslider_Adminhtml_Bannerslider_ReportController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('bannerslider/report')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
        return $this;
    }

    /**
     * index action
     */
    public function indexAction() {
        $this->_initAction()
                ->renderLayout();
    }
    
    public function bannerAction(){
        $this->_initAction()
                ->renderLayout();
    }
    /**
     * mass delete item(s) action
     */
    public function massDeleteAction() {
        $bannersliderIds = $this->getRequest()->getParam('bannerslider');
        if (!is_array($bannersliderIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($bannersliderIds as $bannersliderId) {
                    $bannerslider = Mage::getModel('bannerslider/report')->load($bannersliderId);
                    $bannerslider->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Total of %d record(s) were successfully deleted', count($bannersliderIds)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
    
    //for slider and banner
    /**
     * export grid item to CSV type
     */
     public function exportCsvAction() {
        $fileName = 'bannerslider_report_slider.csv';
        $content = $this->getLayout()->createBlock('bannerslider/adminhtml_report_grid')->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    /**
     * export grid item to XML type
     */
    public function exportXmlAction() {
        $fileName = 'bannerslider_report_slider.xml';
        $content = $this->getLayout()->createBlock('bannerslider/adminhtml_report_grid')->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    
    //Only Banner
    public function exportCsvBannerAction() {
        $fileName = 'bannerslider_report_onlybanner.csv';
        $content = $this->getLayout()->createBlock('bannerslider/adminhtml_report_banner_grid')->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    /**
     * export grid item to XML type
     */
    public function exportXmlBannerAction() {
        $fileName = 'bannerslider_report_onlybanner.xml';
        $content = $this->getLayout()->createBlock('bannerslider/adminhtml_report_banner_grid')->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('bannerslider');
    }
}