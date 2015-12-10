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
 * Bannerslider Resource Collection Model
 * 
 * @category 	Magestore
 * @package 	Magestore_Bannerslider
 * @author  	Magestore Developer
 */
class Magestore_Bannerslider_Model_Mysql4_Banner_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {

    protected $_store_id = null;
    protected $_addedTable = array();

    public function _construct() {
        parent::_construct();
        if ($storeId = Mage::app()->getStore()->getId()) {
            $this->setStoreId($storeId);
        }		
        $this->_init('bannerslider/banner');
    }

    //use for multi store
    public function addFieldToFilter($field, $condition = null) {
        $attributes = array(
            'name',
            'status',
            'click_url',
        );		
        $storeId = $this->getStoreId();
        if (in_array($field, $attributes) && $storeId) {
            if (!in_array($field, $this->_addedTable)) {
                $this->getSelect()
                        ->joinLeft(array($field => $this->getTable('bannerslider/value')), "main_table.banner_id = $field.banner_id" .
                                " AND $field.store_id = $storeId" .
                                " AND $field.attribute_code = '$field'", array()
                );
                $this->_addedTable[] = $field;
            }
            $this->getSelect()->where("IF($field.value IS NULL, main_table.$field, $field.value) = $condition");
                return $this;
            // return parent::addFieldToFilter("IF(faq_value_$field.value IS NULL, main_table.$field, faq_value_$field.value)", $condition);
        }
        if ($field == 'store_id') {
            $field = 'main_table.banner_id';
        }
        return parent::addFieldToFilter($field, $condition);
    }

    public function setStoreId($value) {
        $this->_store_id = $value;
        return $this;
    }

    public function getStoreId() {
        return $this->_store_id;
    }

    //multi store
    protected function _afterLoad() {
        parent::_afterLoad();
        if ($storeId = $this->getStoreId()) {
            foreach ($this->_items as $item) {
                $item->setStoreId($storeId)->getMultiStoreValue();
            }
        }
        return $this;
    }

}