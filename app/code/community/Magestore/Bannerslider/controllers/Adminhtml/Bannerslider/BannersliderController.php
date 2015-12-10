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
class Magestore_Bannerslider_Adminhtml_Bannerslider_BannersliderController extends Mage_Adminhtml_Controller_Action {

    /**
     * init layout and set active for current menu
     *
     * @return Magestore_Bannerslider_Adminhtml_BannersliderController
     */
    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('bannerslider/bannerslider')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Slider Manager'), Mage::helper('adminhtml')->__('Slider Manager'));
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
        $model = Mage::getModel('bannerslider/bannerslider')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data))
                $model->setData($data);

            Mage::register('bannerslider_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('bannerslider/bannerslider');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Slider Manager'), Mage::helper('adminhtml')->__('Slider Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('bannerslider/adminhtml_bannerslider_edit'))
                    ->_addLeft($this->getLayout()->createBlock('bannerslider/adminhtml_bannerslider_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('bannerslider')->__('Slider does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction() {
        $this->_forward('edit');
    }

    /**
     * save item action
     */
    public function saveAction() {
        if ($data = $this->getRequest()->getPost()) {

            if ($data['style_slide'] == 1 || $data['style_slide'] == 3 ||
                    $data['style_slide'] == 2 || $data['style_slide'] == 4) {
                $data['animationB'] = $data['animationA'];
                //die('1111');
            }elseif($data['style_slide'] == 5){
                $data['position'] = 'pop-up';
            }elseif($data['style_slide'] == 6){
                $data['position'] = 'note-allsite';
            }
                                    
            //Zend_debug::dump($data);	
            $model = Mage::getModel('bannerslider/bannerslider');
            $model->setData($data)
                    ->setId($this->getRequest()->getParam('id'));
            //Zend_debug::dump($model->getData());die();
            try {

                $model->save();
                if (isset($data['slider_banner'])) {
                    $bannerIds = array();
                    $bannerOrders = array();

                    $test = Mage::helper('adminhtml/js')->decodeGridSerializedInput($data['slider_banner']);
                    foreach ($test as $key => $value) {
                        $bannerIds[] = $key;
                        $bannerOrders[] = $value['order_banner_slider'];
                    }

                    $unSelecteds = Mage::getResourceModel('bannerslider/banner_collection')
                            ->addFieldToFilter('bannerslider_id', $model->getId());
                    if (count($bannerIds))
                        $unSelecteds->addFieldToFilter('banner_id', array('nin' => $bannerIds));
                    foreach ($unSelecteds as $banner) {
                        $banner->setBannersliderId(0)
                                ->setOrderBanner(0)->save();
                    }
                    $selectBanner = Mage::getResourceModel('bannerslider/banner_collection')
                            ->addFieldToFilter('banner_id', array('in' => $bannerIds));
                    //->addFieldToFilter('bannerslider_id', array('neq' => $model->getId()));					
                    $i = -1;
                    foreach ($selectBanner as $banner) {
                        $banner->setBannersliderId($model->getId())
                                ->setOrderBanner($bannerOrders[++$i])->save();
                    }
                    //Zend_debug::dump($selectBanner->getData());die();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('bannerslider')->__('Slider was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
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
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('bannerslider')->__('Unable to find slider to save'));
        $this->_redirect('*/*/');
    }

    /**
     * delete item action
     */
    public function deleteAction() {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('bannerslider/bannerslider');
                $model->setId($this->getRequest()->getParam('id'))
                        ->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Slider was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
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
                    $bannerslider = Mage::getModel('bannerslider/bannerslider')->load($bannersliderId);
                    $bannerslider->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Total of %d record(s) were successfully deleted', count($bannersliderIds)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass change status for item(s) action
     */
    public function massStatusAction() {
        $bannersliderIds = $this->getRequest()->getParam('bannerslider');
        if (!is_array($bannersliderIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($bannersliderIds as $bannersliderId) {
                    $bannerslider = Mage::getSingleton('bannerslider/bannerslider')
                            ->load($bannersliderId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) were successfully updated', count($bannersliderIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * export grid item to CSV type
     */
    public function exportCsvAction() {
        $fileName = 'bannerslider.csv';
        $content = $this->getLayout()->createBlock('bannerslider/adminhtml_bannerslider_grid')->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export grid item to XML type
     */
    public function exportXmlAction() {
        $fileName = 'bannerslider.xml';
        $content = $this->getLayout()->createBlock('bannerslider/adminhtml_bannerslider_grid')->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('bannerslider');
    }

    protected function customAction() {
        $this->loadLayout();
        $this->getLayout()->getBlock('slider.edit.tab.custom')
                ->setCustom($this->getRequest()->getPost('banner', null));
        $this->renderLayout();
    }

    public function customgridAction() {
        $this->loadLayout();
        $this->getLayout()->getBlock('slider.edit.tab.custom')
                ->setCustom($this->getRequest()->getPost('banner', null));
        $this->renderLayout();
    }

    public function chooserMainCategoriesAction() {
        $request = $this->getRequest();
        $ids = $request->getParam('selected', array());

        if (is_array($ids)) {
            foreach ($ids as $key => &$id) {
                $id = (int) $id;
                if ($id <= 0) {
                    unset($ids[$key]);
                }
            }

            $ids = array_unique($ids);
        } else {
            $ids = array();
        }

        $block = $this->getLayout()->createBlock('bannerslider/adminhtml_bannerslider_edit_tab_content_categories', 'content_category', array('js_form_object' => $request->getParam('form')))
                ->setCategoryIds($ids)
        ;

        if ($block) {
            $this->getResponse()->setBody($block->toHtml());
        }
    }

    public function categoriesJsonAction() {
        if ($categoryId = (int) $this->getRequest()->getPost('id')) {
            $this->getRequest()->setParam('id', $categoryId);

            if (!$category = $this->_initCategory()) {
                return;
            }
            $this->getResponse()->setBody(
                    $this->getLayout()->createBlock('adminhtml/catalog_category_tree')
                            ->getTreeJson($category)
            );
        }
    }

    protected function _initCategory() {
        $categoryId = (int) $this->getRequest()->getParam('id', false);
        $storeId = (int) $this->getRequest()->getParam('store');

        $category = Mage::getModel('catalog/category');
        $category->setStoreId($storeId);

        if ($categoryId) {
            $category->load($categoryId);
            if ($storeId) {
                $rootId = Mage::app()->getStore($storeId)->getRootCategoryId();
                if (!in_array($rootId, $category->getPathIds())) {
                    $this->_redirect('*/*/', array('_current' => true, 'id' => null));
                    return false;
                }
            }
        }

        Mage::register('category', $category);
        Mage::register('current_category', $category);

        return $category;
    }

}