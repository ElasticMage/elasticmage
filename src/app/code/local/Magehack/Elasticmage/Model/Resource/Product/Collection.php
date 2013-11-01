<?php

class Magehack_Elasticmage_Model_Resource_Product_Collection extends Mage_Catalog_Model_Resource_Product_Collection
{

    private $conditions = array();

    public function __construct($options = array())
    {
        parent::__construct();
        if(isset($options['elasticsearch'])){
            $this->_elasticsearch = $options['elasticsearch'];
        }else{
            $this->_elasticsearch = Mage::getSingleton('magehack_elasticmage/elasticsearch');
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

    protected function _productLimitationJoinStore()
    {
        return $this;
    }

    protected function _productLimitationJoinWebsite()
    {
        return $this;
    }

    protected function _productLimitationJoinPrice()
    {
        return $this;
    }

    protected function _applyProductLimitations()
    {
        Mage::dispatchEvent('catalog_product_collection_apply_limitations_before', array(
            'collection'  => $this,
            'category_id' => isset($this->_productLimitationFilters['category_id'])
                    ? $this->_productLimitationFilters['category_id']
                    : null,
        ));

        $conditions = array();

        $this->_prepareProductLimitationFilters();

        if (isset($this->_productLimitationFilters['website_ids'])) {
//TODO: Implement IN search
/*
            //$conditions['website_id'] = $this->_productLimitationFilters['website_ids'];
*/
        } elseif (isset($this->_productLimitationFilters['store_id'])
            && (!isset($this->_productLimitationFilters['visibility']) && !isset($this->_productLimitationFilters['category_id']))
            && !$this->isEnabledFlat()
        ) {
            $conditions['website_id'] = Mage::app()->getStore($this->_productLimitationFilters['store_id'])->getWebsiteId();
        }

        if (isset($this->_productLimitationFilters['website_id'])) {
            $conditions['website_id'] = $this->_productLimitationFilters['website_id'];
        }

        if (isset($this->_productLimitationFilters['customer_group_id'])) {
            $conditions['customer_group_id'] = $this->_productLimitationFilters['customer_group_id'];
        }

/*
  //TODO: Implement price index filter
        if (!isset($fromPart['price_index'])) {
            $least       = $connection->getLeastSql(array('price_index.min_price', 'price_index.tier_price'));
            $minimalExpr = $connection->getCheckSql('price_index.tier_price IS NOT NULL',
                $least, 'price_index.min_price');
            $colls       = array('price', 'tax_class_id', 'final_price',
                'minimal_price' => $minimalExpr , 'min_price', 'max_price', 'tier_price');
            $tableName = array('price_index' => $this->getTable('catalog/product_index_price'));
            if ($joinLeft) {
                $select->joinLeft($tableName, $joinCond, $colls);
            } else {
                $select->join($tableName, $joinCond, $colls);
            }
            // Set additional field filters
            foreach ($this->_priceDataFieldFilters as $filterData) {
                $select->where(call_user_func_array('sprintf', $filterData));
            }
        } else {
            $fromPart['price_index']['joinCondition'] = $joinCond;
            $select->setPart(Zend_Db_Select::FROM, $fromPart);
        }
        //Clean duplicated fields
        $helper->prepareColumnsList($select);
*/

        if (!isset($this->_productLimitationFilters['category_id']) && !isset($this->_productLimitationFilters['visibility'])) {
            return $this;
        }

        //$conditions['store_id'] = $this->_productLimitationFilters['store_id'];

        //TODO: Implement IN search
/*
        if (isset($this->_productLimitationFilters['visibility']) && !isset($this->_productLimitationFilters['store_table'])) {
            $conditions['visibility'] = $this->_productLimitationFilters['visibility'];
        }
*/
        if (!$this->getFlag('disable_root_category_filter')) {
            $conditions['categories'] = $this->_productLimitationFilters['category_id'];
        }

//        if (isset($this->_productLimitationFilters['category_is_anchor'])) {
//            $conditions['is_parent'] = $this->_productLimitationFilters['category_is_anchor'];
//        }

        $sort = array(
            array('cat_index_position' => 'asc')
        );

        //disable the filters temporarily
        //unset($conditions['store_id']);

        $this->conditions = $conditions;

        Mage::dispatchEvent('catalog_product_collection_apply_limitations_after', array(
            'collection' => $this
        ));

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
        Mage::dispatchEvent('eav_collection_abstract_load_before', array('collection' => $this));
        $this->_beforeLoad();

        $this->_renderFilters();
        $this->_renderOrders();

        $this->_loadEntities($printQuery, $logQuery);

        foreach ($this->_items as $item) {
            $item->setOrigData();
        }

        $this->_setIsLoaded();
        $this->_afterLoad();
        return $this;
    }


    public function _loadEntities($printQuery = false, $logQuery = false)
    {
        $data = $this->_elasticsearch->getProductData(
            $this->_calculateFromParameter(
                $this->_curPage, $this->_pageSize
            ),
            (int) $this->_pageSize,
            $this->conditions
        );

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
        return $this->_elasticsearch->getProductCount($this->conditions);
    }

    private function _calculateFromParameter($page, $pageSize)
    {
        return (int) (($page-1) * (int)$pageSize);
    }

}
