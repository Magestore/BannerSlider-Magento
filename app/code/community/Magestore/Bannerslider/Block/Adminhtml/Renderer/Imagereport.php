<?php

class Magestore_Bannerslider_Block_Adminhtml_Renderer_Imagereport extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {
    /* Render Grid Column */

    public function render(Varien_Object $row) {
        $banner_id = $row->getData('banner_id');
	$bannerImage = Mage::getModel('bannerslider/banner')->load($banner_id)->getImage();
        $imagename = Mage::helper('bannerslider')->getBannerImage($bannerImage);
        return
                '<img id="image_banner' . $banner_id . $row->getId().'" src="' . $imagename . '" width="50px" height="50px"/>'.
                '<script type="text/javascript">
                    new Tooltip("image_banner'.$banner_id.$row->getId().'", "'.$imagename.'");
                    $$(".tooltip img").each(function(item){
                        item.style.width="300px";
                         item.style.height="300px";
                    });
                </script>'
                
        ;
    }

}