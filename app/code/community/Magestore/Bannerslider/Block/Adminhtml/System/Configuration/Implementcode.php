<?php

class Magestore_Bannerslider_Block_Adminhtml_System_Configuration_Implementcode extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element){
       // $layout  =  Mage::helper('bannerslider')->returnlayout();
        //$block = Mage::helper('bannerslider')->returnblock();
        //$text =  Mage::helper('bannerslider')->returntext();
       // $template = Mage::helper('bannerslider')->returntemplate();
        return '
<div class="entry-edit-head collapseable"><a onclick="Fieldset.toggleCollapse(\'bannerslider_template\'); return false;" href="#" id="bannerslider_template-head" class="open">Implement Code</a></div>
<input id="bannerslider_template-state" type="hidden" value="1" name="config_state[bannerslider_template]">
<fieldset id="bannerslider_template" class="config collapseable" style="">
<h4 class="icon-head head-edit-form fieldset-legend">Code for BannerSlider</h4>

<div id="messages">
    <ul class="messages">
        <li class="notice-msg">
            <ul>
                <li>'.Mage::helper('bannerslider')->__('Add code below to a template file').'</li>				
            </ul>
        </li>
    </ul>
</div>
<br>
<ul>
	<li>
		<code>
			$this->getLayout()->createBlock('.'bannerslider/default'.')->setTemplate('.'bannerslider/bannerslider.phtml'.')->setBannersliderId(your_bannerslider_id)->toHtml();
		</code>
	</li>
</ul>
<br>
<div id="messages">
    <ul class="messages">
        <li class="notice-msg">
            <ul>
                <li>'.Mage::helper('bannerslider')->__('You can put a banner slider on a cms page. Below is an example which we put a banner slider with bannerslider_id is your_bannerslider_id on a cms page').'</li>				
            </ul>
        </li>
    </ul>
</div>
<br>
<ul>
	<li>
		<code>
			{{block type="bannerslider/default" name="bannerslider.bannerslider" template="bannerslider/bannerslider.phtml" bannerslider_id="your_bannerslider_id"}}
		</code>
	</li>
</ul>
<br>
<div id="messages">
    <ul class="messages">
        <li class="notice-msg">
            <ul>
                <li>'.Mage::helper('bannerslider')->__('Please copy and paste the code below on one of xml layout files where you want to show the banner. Please replace the your_bannerslider_ids variable with your own bannerslider Id').'</li>				
            </ul>
        </li>
    </ul>
</div>

<ul>
	<li>
		<code>
		 &lt;block type="bannerslider/default" name="bannerslider.bannerslider" template="bannerslider/bannerslider.phtml"&gt;<br>
		&nbsp;&nbsp;&nbsp;&nbsp;&lt;action method="setBannersliderId"&gt;&lt;bannerslider_id&gt;your_bannerslider_id&lt;/bannerslider_id&gt;&lt;/action&gt;<br>
		&lt;/block&gt;
		</code>	
	</li>
</ul>
<br>
<div id="messages">
    <ul class="messages">
        <li class="notice-msg">
            <ul>			
                <li>'.Mage::helper('bannerslider')->__('Below is an example to show a banner slider with your_bannerslider_id on the left of the category page.').'</li>				
            </ul>
        </li>
    </ul>
</div>
<br>
<ul>
	<li>
		<code>
&lt;?xml version="1.0"?&gt;<br>
&lt;layout version="0.1.0"&gt;<br>
&nbsp;&nbsp;&lt;catalog_category_default&gt;<br>
	&nbsp;&nbsp;&nbsp;&nbsp;&lt;reference name="left"&gt;<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;block type="catalog/navigation" name="catalog.leftnav" after="currency" template="catalog/navigation/left.phtml"/&gt;<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;block type="bannerslider/default" name="bannerslider.block" template="bannerslider/bannerslider.phtml"&gt;<br>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;action method="setBannersliderId"&gt;&lt;bannerslider_id&gt;your_bannerslider_id&lt;/bannerslider_id&gt;&lt;/action&gt;<br>
		   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/block&gt; <br>
	&nbsp;&nbsp;&nbsp;&nbsp;&lt;/reference><br>
&nbsp;&nbsp;&lt;/catalog_category_default&gt;<br>
&lt;/layout&gt;
</code>	
	</li>
</ul>
<br>

</fieldset>';
    }
}
