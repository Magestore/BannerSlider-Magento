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
 * Bannerslider Index Controller
 * 
 * @category 	Magestore
 * @package 	Magestore_Bannerslider
 * @author  	Magestore Developer
 */
class Magestore_Bannerslider_IndexController extends Mage_Core_Controller_Front_Action {

    /**
     * index action
     */
    public function indexAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function preDispatch() {
//            if(!Mage::getStoreConfig('bannerslider/general/enable')){			
//                 header('Location: '.Mage::getUrl());          
//                 exit;      				
//            }else{                
//                return $this;
//            }
    }

    public function clickAction() {
        $cookie = Mage::getSingleton('core/cookie');
        $id = $this->getRequest()->getParam('id_banner');
        $userCode = $this->getUserCode($id);
        $slider_id = $this->getRequest()->getParam('slider_id');
        $date_click = date('Y-m-d');
        $report = Mage::getModel('bannerslider/report');
        $collection = $report->getCollection();
        if ($id && $slider_id) {
            if (!$cookie->get('bannerslider_user_code_click' . $id)) {
                $cookie->set('bannerslider_user_code_click' . $id, $userCode);
                $collection->addFieldToFilter('banner_id', $id)
                        ->addFieldToFilter('bannerslider_id', $slider_id)
                        ->addFieldToFilter('date_click', $date_click);
                if (count($collection)) {
                    foreach ($collection as $item) {
                        $item->setData('clicks', $item->getData('clicks') + 1);
                        $item->save();
                    }
                } else {
                    $report->setData('banner_id', $id)
                            ->setData('bannerslider_id', $slider_id)
                            ->setData('date_click', $date_click)
                            ->setData('clicks', $report->getData('clicks') + 1);
                    try {
                        $report->save();
                    } catch (Exception $e) {
                        
                    }
                }
            }
        }
    }

    public function impressAction() {
        $cookie = Mage::getSingleton('core/cookie');
        $userCode = $this->getUserCode(null);
        $banner_ids = $this->getRequest()->getParam("banner_ids");
        $banner_id_arr = explode(",", $banner_ids);
        $banner_popup_id = $this->getRequest()->getParam("banner_popup_id");
        $slider_id = $this->getRequest()->getParam("slider_id");
        $date_click = date('Y-m-d');
        $report = Mage::getModel('bannerslider/report');
        if ($slider_id && $date_click) {
            if (!$cookie->get('bannerslider_user_code_impress' . $slider_id)) {
                $cookie->set('bannerslider_user_code_impress' . $slider_id, $userCode);
                $collection = $report->getCollection();
                $collection ->addFieldToFilter('date_click', $date_click)
                         ->addFieldToFilter('bannerslider_id', $slider_id)
                        ->addFieldToFilter('banner_id', array('in' => $banner_id_arr));
                if (count($collection->getData())) {
                    $bannerIdsCache= array();                   
                    foreach ($collection as $item) {
                        $item->setData('impmode', $item->getData('impmode') + 1);
                        try {
                            $item->save();
                            $bannerIdsCache[] = $item->getBannerId();
                        } catch (Exception $e) {
                            
                        }
                    }
                    $otherBannerIds = array_diff($banner_id_arr, $bannerIdsCache);
                    if(count($otherBannerIds)){
                        
                        foreach ($otherBannerIds as $b_id) {
                            
                            $report_b = Mage::getModel('bannerslider/report');
                            $report_b->setData('banner_id', $b_id)
                                    ->setData('bannerslider_id', $slider_id)
                                    ->setData('impmode', $report_b->getData('impmode') + 1)
                                    ->setData('date_click', $date_click);
                            try {
                                $report_b->save();
                            } catch (Exception $e) {
                                
                            }
                        }
                    }
                } else {
                    if (count($banner_ids) && isset($banner_ids)) {
                        foreach ($banner_id_arr as $banner_id) {
                            $report_b = Mage::getModel('bannerslider/report');
                            $report_b->setData('banner_id', $banner_id)
                                    ->setData('bannerslider_id', $slider_id)
                                    ->setData('impmode', $report_b->getData('impmode') + 1)
                                    ->setData('date_click', $date_click);
                            try {
                                $report_b->save();
                            } catch (Exception $e) {
                                
                            }
                        }
                    }
                    if ($banner_popup_id) {
                        $report_b = Mage::getModel('bannerslider/report');
                        $report_b->setData('banner_id', $banner_popup_id)
                                ->setData('bannerslider_id', $slider_id)
                                ->setData('impmode', $report_b->getData('impmode') + 1)
                                ->setData('date_click', $date_click);
                        try {
                            $report_b->save();
                        } catch (Exception $e) {
                            
                        }
                    }
                }
            }
        }
    }

    private function getUserCode($id) {
        $ipAddress = null;
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ipAddress = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $ipAddress = $_SERVER["HTTP_CLIENT_IP"];
        } elseif (isset($_SERVER["REMOTE_ADDR"])) {
            $ipAddress = $_SERVER["REMOTE_ADDR"];
        }

        $cookiefrontend = $_COOKIE['frontend'];
        $usercode = $ipAddress . $cookiefrontend . $id;
        return md5($usercode);
    }
}