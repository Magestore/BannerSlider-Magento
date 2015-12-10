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
 * Bannerslider Model
 * 
 * @category 	Magestore
 * @package 	Magestore_Bannerslider
 * @author  	Magestore Developer
 */
class Magestore_Bannerslider_Model_Banner extends Mage_Core_Model_Abstract {

    protected $_store_id = null;

    public function _construct() {
        parent::_construct();
        if ($storeId = Mage::app()->getStore()->getId()) {
            $this->setStoreId($storeId);
        }
        $this->_init('bannerslider/banner');
    }

    protected function _beforeSave() {
        if ($storeId = $this->getStoreId()) {
            $defaultStore = Mage::getModel('bannerslider/banner')->load($this->getId());
            $storeAttributes = $this->getStoreAttributes();
            foreach ($storeAttributes as $attribute) {
                if ($this->getData($attribute . '_default')) {
                    $this->setData($attribute . '_in_store', false);
                } else {
                    $this->setData($attribute . '_in_store', true);
                    $this->setData($attribute . '_value', $this->getData($attribute));
                }
                $this->setData($attribute, $defaultStore->getData($attribute));
            }
        }
        return parent::_beforeSave();
    }

    protected function _afterSave() {
        if ($storeId = $this->getStoreId()) {
            $storeAttributes = $this->getStoreAttributes();

            foreach ($storeAttributes as $attribute) {
                $attributeValue = Mage::getModel('bannerslider/value')
                        ->loadAttributeValue($this->getId(), $storeId, $attribute);
                if ($this->getData($attribute . '_in_store')) {
                    try {
                        $attributeValue->setValue($this->getData($attribute . '_value'))->save();
                    } catch (Exception $e) {
                        
                    }
                } elseif ($attributeValue && $attributeValue->getId()) {
                    try {
                        $attributeValue->delete();
                    } catch (Exception $e) {
                        
                    }
                }
            }
        }
        return parent::_afterSave();
    }

    public function getStoreId() {
        return $this->_store_id;
    }

    public function setStoreId($id) {
        $this->_store_id = $id;
        return $this;
    }

    public function getStoreAttributes() {
        return array(
            'name',
            'status',
            'click_url',
        );
    }

    //info multistore
    public function load($id, $field = null) {
        parent::load($id, $field);
        if ($this->getStoreId()) {
            $this->getMultiStoreValue();
        }
        return $this;
    }

    public function getMultiStoreValue($storeId = null) {
        if (!$storeId) {
            $storeId = $this->getStoreId();
        }
        if (!$storeId) {
            return $this;
        }
        $storeValues = Mage::getModel('bannerslider/value')->getCollection()
                ->addFieldToFilter('banner_id', $this->getId())
                ->addFieldToFilter('store_id', $storeId);
        foreach ($storeValues as $value) {
            $this->setData($value->getAttributeCode() . '_in_store', true);
            $this->setData($value->getAttributeCode(), $value->getValue());
        }
        return $this;
    }

}