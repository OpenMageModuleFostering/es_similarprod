<?php
class ES_Similarprod_Block_Products extends Mage_Catalog_Block_Product_Abstract
{
    protected $_currentProductId;

    protected function getCategoryId()
    {
        if (!$this->_currentProductId) {
            $productId = Mage::registry('current_product')->getId();
            if (!is_numeric($productId))
                return;
            $this->_currentProductId = $productId;
        }

        $model = Mage::getModel('catalog/product'); //getting product model
        $product = $model->load($this->_currentProductId);
        $categoriesIds = $product->getCategoryIds();
        if (count($categoriesIds) == 0)
            return;

        return $categoriesIds[0];
    }

    protected function getProductList()
    {
        $categoryId = $this->getCategoryId();
        if (!is_numeric($categoryId))
            return;

        $category = Mage::getModel('catalog/category')->load($categoryId);
        $productList = $category->getProductCollection()
            ->addIdFilter($this->_currentProductId, true)
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('short_description')
            ->addAttributeToSelect('price')
            ->addAttributeToSelect('special_price')
            ->addAttributeToSelect('thumbnail')
            ->addAttributeToSelect('is_salable')
            ->setPageSize(Mage::getStoreConfig('similarprod/general/perblock'));
        return $productList;
    }

    protected function getBlockTitle()
    {
        return Mage::getStoreConfig('similarprod/general/title');
    }

    protected function activeRightBlock()
    {
        return Mage::getStoreConfig('similarprod/general/blockright');
    }

    protected function activeLeftBlock()
    {
        return Mage::getStoreConfig('similarprod/general/blockleft');
    }
}