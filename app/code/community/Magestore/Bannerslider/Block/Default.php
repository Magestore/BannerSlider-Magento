<?php

class Magestore_Bannerslider_Block_Default extends Mage_Core_Block_Template {

    public function _prepareLayout() {
        return parent::_prepareLayout();
    }

    public function getBlockData() {
        if (!$this->hasData('block_data')) {
            $bannerslider_id = $this->getBannersliderId();
            if ($bannerslider_id) {
                $block_data = Mage::getModel('bannerslider/bannerslider')->load($bannerslider_id);
            } else {
                $block_data = $this->getSliderData();
            }
            $category = Mage::registry('current_category');
            $cateIds = array();
            if ($category) {
                $cateIds = $category->getPathIds();
                $categoryIds = $block_data->getCategoryIds();
                $categoryIds = explode(",", $categoryIds);
                if (strncasecmp('category', $block_data->getPosition(), 8) == 0) {
                    if (count(array_intersect($cateIds, $categoryIds)) == 0) {
                        $block_data = null;
                        return null;
                    }
                }
            }
			$today=Mage::getModel('core/date')->gmtDate();
            $randomise = $block_data->getSortType() ? false : true;
            $banners = Mage::getModel('bannerslider/banner')->getCollection()
                    ->addFieldToFilter('bannerslider_id', $block_data->getId())
                    ->addFieldToFilter('status', 0)                   
                    ->addFieldToFilter('start_time', array('lteq' => $today))
                    ->addFieldToFilter('end_time', array('gteq' => $today))
                   ->setOrder('order_banner', "ASC");
           $banners->getSelect()->columns(array($randomise ? 'Rand() as order' : ''));

            
            $result = array();
            $result['block'] = $block_data;
            $result['banners'] = array();
            foreach ($banners as $banner){
                $result['banners'][] = $banner->getData();
            }                      
            $this->setData('block_data', $result);
        }
        return $this->getData('block_data');
    }

    public function returntemplateSlider($style, $result) {
        $html = '';
        switch ($style) {
            case "1":
                $html = $this->getLayout()->createBlock('bannerslider/default')->setBlockData($result)->setTemplate('bannerslider/slider1.phtml')->toHtml();
                break;
            case "2":
                $html = $this->getLayout()->createBlock('bannerslider/default')->setBlockData($result)->setTemplate('bannerslider/slider2.phtml')->toHtml();
                break;
            case "3":
                $html = $this->getLayout()->createBlock('bannerslider/default')->setBlockData($result)->setTemplate('bannerslider/slider3.phtml')->toHtml();
                break;
            case "4":
                $html = $this->getLayout()->createBlock('bannerslider/default')->setBlockData($result)->setTemplate('bannerslider/slider4.phtml')->toHtml();
                break;
            case "5":
                $html = $this->getLayout()->createBlock('bannerslider/default')->setBlockData($result)->setTemplate('bannerslider/slider5.phtml')->toHtml();
                break;
            case "6":
                $html = $this->getLayout()->createBlock('bannerslider/default')->setBlockData($result)->setTemplate('bannerslider/slider6.phtml')->toHtml();
                break;
            case "7":
                $html = $this->getLayout()->createBlock('bannerslider/default')->setBlockData($result)->setTemplate('bannerslider/slider7.phtml')->toHtml();
                break;
            case "8":
                $html = $this->getLayout()->createBlock('bannerslider/default')->setBlockData($result)->setTemplate('bannerslider/slider8.phtml')->toHtml();
                break;
            case "9":
                $html = $this->getLayout()->createBlock('bannerslider/default')->setBlockData($result)->setTemplate('bannerslider/slider9.phtml')->toHtml();
                break;
            case "10":
                $html = $this->getLayout()->createBlock('bannerslider/default')->setBlockData($result)->setTemplate('bannerslider/slider10.phtml')->toHtml();
                break;
            case "11":
                $html = $this->getLayout()->createBlock('bannerslider/default')->setBlockData($result)->setTemplate('bannerslider/popup.phtml')->toHtml();
                break;
            case "12":
                $html = $this->getLayout()->createBlock('bannerslider/default')->setBlockData($result)->setTemplate('bannerslider/note.phtml')->toHtml();
                break;
            default :
                $html = '';
        }
        return $html;
    }

    public function getBannerImage($imageName) {
        return Mage::helper('bannerslider')->getBannerImage($imageName);
    }

    public function getTarget($x) {
        if ($x == 0) {
            return '_self';
        } elseif ($x == 1) {
            return '_parent';
        } else {
            return '_blank';
        }
    }

    public function getMinItem($value) {
        if (!$value)
            return 2;
        return $value;
    }

    public function getMaxItem($value) {
        if (!$value)
            return 4;
        if ($value > 12)
            return 12;
        return $value;
    }

    public function isDisplayPopup() {
        $cookie = Mage::getSingleton('core/cookie');
        if ($cookie->get('isdisplaypopup')) {
            return false;
        } else {
            setcookie("isdisplaypopup", 'true', time() + 120, "/");
            return true;
        }
    }

}