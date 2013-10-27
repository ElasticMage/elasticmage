<?php

class Magehack_Elasticmage_Model_Resource_Product_Collection extends Mage_Catalog_Model_Resource_Product_Collection
{

    public function __construct($options = array())
    {
        parent::__construct();
        if(isset($options['elasticsearch'])){
            $this->_elasticsearch = $options['elasticsearch'];
        }else{
            $this->_elasticsearch = Mage::getModel('magehack_elasticmage/elasticsearch');
        }
    }

    protected function _getReadAdapter()
    {
        return Mage::getSingleton('core/resource')->getConnection('core_read');
    }

    protected function _initTables()
    {
        return $this;
    }

    protected function _applyProductLimitations()
    {
        //TODO:implement
        return $this;
    }

    /**
     * Apply limitation filters to collection base on API
     * Method allows using one time category product table
     * for combinations of category_id filter states
     *
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    protected function _applyZeroStoreProductLimitations()
    {
        //TODO:implement
        return $this;
    }

    public function getCatalogPreparedSelect()
    {
        //TODO:CHECK
        return new Varien_Db_Select($this->_getReadAdapter());
    }

    protected function _preparePriceExpressionParameters($select)
    {
        //TODO:CHECK
        return $this;
    }

    public function getPriceExpression($select = null)
    {
        //TODO:CHECK
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

    public function _loadAttributes($printQuery = false, $logQuery = false)
    {
        return $this;
    }

    public function addAttributeToSelect($attribute, $joinType = 'inner')
    {
        return $this;
    }

    public function addIdFilter($productId, $exclude = false)
    {
        //TODO: implement
        return $this;
    }

    public function addWebsiteNamesToResult()
    {
        return $this;
    }

    public function getLimitationFilters()
    {
        //TODO: implement
        return array();
    }

    public function getMaxAttributeValue($attribute)
    {
        //TODO: implement
        if (!is_null($attribute)) {
            return max($attribute);
        } else {
            return null;
        }
    }

    public function getAttributeValueCountByRange($attribute, $range)
    {
        //TODO: implement
        return array();
    }

    public function getAttributeValueCount($argument1)
    {
        //TODO: implement
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

    public function getAllIds($limit = null, $offset = null)
    {
        return $this->_elasticsearch->getAllIds();
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

    public function addUrlRewrite($categoryId = '')
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

    public function getAllIdsCache($resetCache = false)
    {
        return array();
    }

    public function addPriceData($customerGroupId = null, $websiteId = null)
    {
        return $this;
    }

    public function addAttributeToFilter($attribute, $condition = null, $joinType = 'inner')
    {
        return $this;
    }

    public function addOptionsToResult()
    {
        return $this;
    }

    public function addAttributeToSort($attribute, $dir = Mage_Catalog_Model_Resource_Product_Collection::SORT_ORDER_ASC)
    {
       return $this;
    }

    public function applyFrontendPriceLimitations()
    {
        return $this;
    }

    public function addCategoryIds()
    {
        return $this;
    }

    public function addTierPriceData()
    {
        return $this;
    }

    public function addPriceDataFieldFilter($comparisonFormat, $fields)
    {
        return $this;
    }

    public function clear()
    {
        return $this;
    }

    public function setOrder($attribute, $dir = 'desc')
    {
        return $this;
    }

    public function addSearchFilter($query)
    {
        return $this;
    }

    public function load($printQuery = false, $logQuery = false)
    {
        if ($this->isLoaded()) {
            return $this;
        }
        Varien_Profiler::start('__EAV_COLLECTION_BEFORE_LOAD__');
        Mage::dispatchEvent('eav_collection_abstract_load_before', array('collection' => $this));
        $this->_beforeLoad();
        Varien_Profiler::stop('__EAV_COLLECTION_BEFORE_LOAD__');

        $this->_renderFilters();
        $this->_renderOrders();

        Varien_Profiler::start('__EAV_COLLECTION_LOAD_ENT__');
        $this->_loadEntities($printQuery, $logQuery);
        Varien_Profiler::stop('__EAV_COLLECTION_LOAD_ENT__');

        Varien_Profiler::start('__EAV_COLLECTION_ORIG_DATA__');
        foreach ($this->_items as $item) {
            $item->setOrigData();
        }
        Varien_Profiler::stop('__EAV_COLLECTION_ORIG_DATA__');

        $this->_setIsLoaded();
        Varien_Profiler::start('__EAV_COLLECTION_AFTER_LOAD__');
        $this->_afterLoad();
        Varien_Profiler::stop('__EAV_COLLECTION_AFTER_LOAD__');
        return $this;
    }


    public function _loadEntities($printQuery = false, $logQuery = false)
    {
        $data = $this->_elasticsearch->getProductData();

        foreach ($data as $v) {
            $object = $this->getNewEmptyItem()
                ->setData($v);
            $this->addItem($object);
            if (isset($this->_itemsById[$object->getId()])) {
                $this->_itemsById[$object->getId()][] = $object;
            } else {
                $this->_itemsById[$object->getId()] = array($object);
            }
        }
        return $this;
    }

    public function getSize()
    {
        return $this->_elasticsearch->getProductCount();
    }

}