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

/** @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

/**
 * create bannerslider table
 */
$installer->run("

DROP TABLE IF EXISTS {$this->getTable('bannerslider_banner')};
DROP TABLE IF EXISTS {$this->getTable('bannerslider_slider')};
DROP TABLE IF EXISTS {$this->getTable('bannerslider_report')};
DROP TABLE IF EXISTS {$this->getTable('bannerslider_value')};

CREATE TABLE {$this->getTable('bannerslider_slider')} (
  `bannerslider_id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',  
  `position` varchar(255) NULL ,
  `show_title` tinyint(1) NULL default '0',
  `status` smallint(6) NOT NULL default '0',
  `sort_type` int(11) NULL,
  `description` text NULL,
  `category_ids` varchar(255) NULL,
  `style_content` smallint(6) NOT NULL default '0',
  `custom_code` text NULL,  
  `style_slide` varchar(255) NULL,    
  `width` float(10) NULL,
  `height` float(10) NULL,
  `note_color` varchar(40) NULL,  
  `animationB` varchar(255) NULL,  
  `caption` smallint(6) NULL,
  `position_note` int (11) NULL default '1',
  `slider_speed` float (10) NULL,
  `url_view` varchar(255) NULL,  
  `min_item` int(11) NULL,  
  `max_item` int(11) NULL,  
  PRIMARY KEY (`bannerslider_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE {$this->getTable('bannerslider_banner')} (
  `banner_id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `order_banner` int(11) NULL default '0',
  `bannerslider_id` int(11) NULL,
  `status` smallint(6) NOT NULL default '0',
  `click_url` varchar(255) NULL default '',
  `imptotal` int(11)  NULL default '0',
  `clicktotal` int(11)  NULL default '0',
  `tartget` int(11) NULL default '0', 
  `image`	varchar(255) NULL,
  `image_alt`	varchar(255) NULL,
  `width`	float(10) NULL,
  `height`	float(10) NULL,  
  `start_time` datetime NULL,
  `end_time` datetime NULL,
  PRIMARY KEY (`banner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


CREATE TABLE {$this->getTable('bannerslider_value')} (
  `value_id` int(10) unsigned NOT NULL auto_increment,
  `banner_id` int(11) unsigned NOT NULL,
  `store_id` smallint(5) unsigned  NOT NULL,
  `attribute_code` varchar(63) NOT NULL default '',
  `value` text NOT NULL,
  UNIQUE(`banner_id`,`store_id`,`attribute_code`),
  INDEX (`banner_id`),
  INDEX (`store_id`),
  FOREIGN KEY (`banner_id`) REFERENCES {$this->getTable('bannerslider_banner')} (`banner_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`store_id`) REFERENCES {$this->getTable('core/store')} (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  PRIMARY KEY (`value_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;     

CREATE TABLE {$this->getTable('bannerslider_report')} (
  `report_id` int(11) unsigned NOT NULL auto_increment,
  `banner_id` int(11)  NULL,
  `bannerslider_id` int(11) NULL,
  `impmode` int(11)  NULL default '0',
  `clicks` int(11)  NULL default '0',
  `date_click` datetime NULL,
  PRIMARY KEY (`report_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

");

$installer->endSetup();

