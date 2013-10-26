<?php

class Magehack_Elasticmage_Model_Resource_Product_Collection extends Varien_Object//Mage_Catalog_Model_Resource_Product_Collection
{

    public function __construct($options = array())
    {
        if(isset($options['connection'])){
            $this->_connection = $options['connection'];
        }else{
           // TODO: Insert real Elasticsearch connection initialise here
        }
    }
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

    public function getAttributeValueCountByRange($attribute, $range)
    {
        return array();
    }

    public function getAttributeValueCount($argument1)
    {
        return array();
    }

    public function getAllAttributeValues($argument1)
    {
        return array();
    }

    public function getSelectCountSql()
    {
        return new Varien_Db_Select($this->_getReadAdapter());
    }

    public function getAllIds()
    {
        return array();
    }

    public function getProductCountSelect()
    {
        return new Varien_Db_Select($this->_getReadAdapter());
    }

    public function addCountToCategories($argument1)
    {
        return $this;
    }

    public function getSetIds()
    {
        return array();
    }


    public function getProductTypeIds()
    {
        return array();
    }

    public function joinUrlRewrite()
    {
        return $this;
    }

    public function addUrlRewrite()
    {
        return $this;
    }

    public function addMinimalPrice()
    {
        return $this;
    }

    public function addFinalPrice()
    {
        return $this;
    }

    public function getAllIdsCache()
    {
        return array();
    }
}