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
class Magestore_Bannerslider_Adminhtml_Bannerslider_BannerController extends Mage_Adminhtml_Controller_Action {

    /**
     * init layout and set active for current menu
     *
     * @return Magestore_Bannerslider_Adminhtml_BannersliderController
     */
    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('bannerslider/banner')
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

    /**
     * view and edit item action
     */
    public function editAction() {
        $id = $this->getRequest()->getParam('id');
        $store = $this->getRequest()->getParam('store');
        $model = Mage::getModel('bannerslider/banner')->setStoreId($store)->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data))
                $model->setData($data);

            Mage::register('banner_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('bannerslider/bannerslider');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('bannerslider/adminhtml_banner_edit'))
                    ->_addLeft($this->getLayout()->createBlock('bannerslider/adminhtml_banner_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('bannerslider')->__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function addinAction() {
		$id = $this->getRequest()->getParam('id');
        $store = $this->getRequest()->getParam('store');
        $model = Mage::getModel('bannerslider/banner')->setStoreId($store)->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data))
                $model->setData($data);

            Mage::register('banner_data', $model);
		}
        $this->loadLayout();
        $this->_setActiveMenu('bannerslider/bannerslider');

        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));
         $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('bannerslider/adminhtml_banner_edit'))
                    ->_addLeft($this->getLayout()->createBlock('bannerslider/adminhtml_banner_edit_tabs'));

        $this->renderLayout();
    }

    /**
     * save item action
     */
    public function saveAction() {        
        if ($data = $this->getRequest()->getPost()) {          
			//Zend_debug::dump($data);die();
            $store = $this->getRequest()->getParam('store');
            $model = Mage::getModel('bannerslider/banner');
            if (isset($data['image']['delete'])) {
                Mage::helper('bannerslider')->deleteImageFile($data['image']['value']);
            }
            $image = Mage::helper('bannerslider')->uploadBannerImage();
            if ($image || (isset($data['image']['delete']) && $data['image']['delete'])) {
                $data['image'] = $image;
            } else {
                unset($data['image']);
            }
                              
            try {
                 $data['start_time']=date('Y-m-d H:i:s',Mage::getModel('core/date')->gmtTimestamp(strtotime($data['start_time'])));
                 $data['end_time']=date('Y-m-d H:i:s',Mage::getModel('core/date')->gmtTimestamp(strtotime($data['end_time'])));
            } catch (Exception $e) {}
			
			$model->setOrderBanner("7");
            $model->setData($data)
                    ->setStoreId($store)
                    ->setData('banner_id', $this->getRequest()->getParam('id'));            
            try {
               
                
                $model->save();				 
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('bannerslider')->__('Banner was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                //Zend_debug::dump($this->getRequest()->getParam('slider'));die();
                if($this->getRequest()->getParam('slider') == 'check'){
                    $this->_redirect('*/*/addin', array('id' => $model->getId()));
                    return;
                }
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId(), 'store' => $this->getRequest()->getParam("store")));
                    return;
                }
				 if ($this->getRequest()->getParam('addin')) {
                    $this->_redirect('*/*/addin', array('id' => $model->getId(), 'store' => $this->getRequest()->getParam("store")));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('bannerslider')->__('Unable to find banner to save'));
        $this->_redirect('*/*/');
    }

    /**
     * delete item action
     */
    public function deleteAction() {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('bannerslider/banner');
                $model->setId($this->getRequest()->getParam('id'))
                        ->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Banner was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id'), 'store' => $this->getRequest()->getParam("store")));
            }
        }
        $this->_redirect('*/*/');
    }

    /**
     * mass delete item(s) action
     */
    public function massDeleteAction() {
        $bannersliderIds = $this->getRequest()->getParam('banner');
        if (!is_array($bannersliderIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($bannersliderIds as $bannersliderId) {
                    $bannerslider = Mage::getModel('bannerslider/banner')->load($bannersliderId);
                    $bannerslider->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Total of %d record(s) were successfully deleted', count($bannersliderIds)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index', array('store' => $this->getRequest()->getParam("store")));
    }

    /**
     * mass change status for item(s) action
     */
    public function massStatusAction() {
        $bannerIds = $this->getRequest()->getParam('banner');
        if (!is_array($bannerIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($bannerIds as $bannerId) {
                    $banner = Mage::getSingleton('bannerslider/banner')
                            ->load($bannerId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) were successfully updated', count($bannerIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index', array('store' => $this->getRequest()->getParam("store")));
    }

    /**
     * export grid item to CSV type
     */
    public function exportCsvAction() {
        $fileName = 'bannerslider.csv';
        $content = $this->getLayout()->createBlock('bannerslider/adminhtml_banner_grid')->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export grid item to XML type
     */
    public function exportXmlAction() {
        $fileName = 'bannerslider.xml';
        $content = $this->getLayout()->createBlock('bannerslider/adminhtml_banner_grid')->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('bannerslider');
    }

}