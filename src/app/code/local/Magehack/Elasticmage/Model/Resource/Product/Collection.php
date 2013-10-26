<?php

class Magehack_Elasticmage_Model_Resource_Product_Collection extends Varien_Object//Mage_Catalog_Model_Resource_Product_Collection
{

    protected function _getReadAdapter()
    {
        return Mage::getSingleton('core/resource')->getConnection('core_read');
    }

    public function getCatalogPreparedSelect()
    {
        return new Varien_Db_Select($this->_getReadAdapter());
    }

    protected function _preparePriceExpressionParameters($select)
    {
        return $this;
    }

    public function getPriceExpression($select = null)
    {
        return new Varien_Db_Select($this->_getReadAdapter());
    }

    public function getAdditionalPriceExpression($select = null)
    {
        return new Varien_Db_Select($this->_getReadAdapter());
    }

    public function isEnabledElastic()
    {
        return true;
    }

    public function getNewEmptyItem()
    {
        return new Varien_Object();
    }

    public function _loadAttributes($printQuery = false, $logQuery = false)
    {
        return $this;
    }

    public function addAttributeToSelect()
    {
        return $this;
    }

    public function addIdFilter($productId, $exclude = false)
    {
        return $this;
    }

    public function addWebsiteNamesToResult()
    {
        return $this;
    }

    public function addStoreFilter($store = null)
    {
        return $this;
    }

    public function addWebsiteFilter($websites = null)
    {
        return $this;
    }

    public function getLimitationFilters()
    {
        return array();
    }

    public function addCategoryFilter(Mage_Catalog_Model_Category $category)
    {
        return $this;
    }

    public function joinMinimalPrice()
    {
        return $this;
    }

    public function getMaxAttributeValue($attribute)
    {
        if (!is_null($attribute)) {
            return max($attribute);
        } else {
            return null;
        }
    }
}