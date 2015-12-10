<?php

class Magestore_Bannerslider_Block_Adminhtml_Bannerslider_Edit_Tab_Content_Categories extends Mage_Adminhtml_Block_Catalog_Category_Tree
{
    protected $_selectedIds = array();

    protected function _prepareLayout()
    {
        $this->setTemplate('bannerslider/categories.phtml');
    }
    public function getCategoryIds()
    {
        return $this->_selectedIds;
    }

    public function setCategoryIds($ids)
    {
        if (empty($ids)) {
            $ids = array();
        }
        elseif (!is_array($ids)) {
            $ids = array((int)$ids);
        }
        $this->_selectedIds = $ids;
        return $this;
    }

    /**
     * Get JSON of a tree node or an associative array
     *
     * @param Varien_Data_Tree_Node|array $node
     * @param int $level
     * @return string
     */
    protected function _getNodeJson($node, $level = 1)
    {
        $item = array();
        $item['text']= $this->htmlEscape($node->getName());

        if ($this->_withProductCount) {
             $item['text'].= ' ('.$node->getProductCount().')';
        }
        $item['id']  = $node->getId();
        $item['path'] = $node->getData('path');
        $item['cls'] = 'folder ' . ($node->getIsActive() ? 'active-category' : 'no-active-category');
        $item['allowDrop'] = false;
        $item['allowDrag'] = false;

        if ($node->hasChildren()) {
            $item['children'] = array();
            foreach ($node->getChildren() as $child) {
                $item['children'][] = $this->_getNodeJson($child, $level + 1);
            }
        }

        if (empty($item['children']) && (int)$node->getChildrenCount() > 0) {
            $item['children'] = array();
        }

        if (!empty($item['children'])) {
            $item['expanded'] = true;
        }

        if (in_array($node->getId(), $this->getCategoryIds())) {
            $item['checked'] = true;
        }

        return $item;
    }

    public function getRoot($parentNodeCategory=null, $recursionLevel=3)
    {
        return $this->getRootByIds($this->getCategoryIds());
    }
}


