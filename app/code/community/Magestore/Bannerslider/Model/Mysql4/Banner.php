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
 * Bannerslider Resource Model
 * 
 * @category 	Magestore
 * @package 	Magestore_Bannerslider
 * @author  	Magestore Developer
 */
class Magestore_Bannerslider_Model_Mysql4_Banner extends Mage_Core_Model_Mysql4_Abstract {

    public function _construct() {
        $this->_init('bannerslider/banner', 'banner_id');
    }

    public function getListBannerOfBlock($block) {
        try {
            $randomise = $block->getSortType() ? false : true;
            
            $today = date("Y-m-d");
            $select = $this->_getReadAdapter()->select()
                    ->from($this->getTable('banner'), array('*', $randomise ? 'Rand() as order' : ''))
                    ->where('bannerslider_id=?', $block->getId())
                    ->where('status=?', 1)
                    ->where('start_time <= ?', $today)
                    ->where('end_time >= ?', $today)
                    ->order("order", "ASC");

            $items = $this->_getReadAdapter()->fetchAll($select);
            //Zend_debug::dump($items);
            return $items;
        } catch (Exception $e) {

            return null;
        }
    }

}