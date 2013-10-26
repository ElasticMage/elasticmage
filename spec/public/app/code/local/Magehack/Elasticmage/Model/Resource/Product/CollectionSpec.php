<?php

namespace spec;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class Magehack_Elasticmage_Model_Resource_Product_CollectionSpec extends ObjectBehavior
{
    private $_connection;

    function let(\Elasticsearch\Client $connection)
    {
        $this->_connection = $connection;
        $this->beConstructedWith(array('connection' => $connection));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Magehack_Elasticmage_Model_Resource_Product_Collection');
    }

    function it_should_getCatalogPreparedSelect_returns_varien_db_select()
    {
        $this->getCatalogPreparedSelect()->shouldBeAnInstanceOf('Varien_Db_Select');
    }

    function it_should_getPriceExpression_return_varien_db_select()
    {
        $this->getPriceExpression()->shouldBeAnInstanceOf('Varien_Db_Select');
    }

    function it_should_getAdditionalPriceExpression_return_varien_db_select()
    {
        $this->getAdditionalPriceExpression()->shouldBeAnInstanceOf('Varien_Db_Select');
    }

    function it_should_isEnabledElastic_return_true()
    {
        $this->isEnabledElastic()->shouldBe(true);
    }

    function it_should_getNewEmptyItem_return_varien_object()
    {
        $this->getNewEmptyItem()->shouldBeAnInstanceOf('Varien_Object');
    }

    function it_should_setEntity_return_varien_object()
    {
        $this->getNewEmptyItem()->shouldBeAnInstanceOf('Varien_Object');
    }

    function it_should_loadAttributes_return_with_Magehack_Elasticmage_Model_Resource_Product_Collection()
    {
        $this->_loadAttributes()->shouldBeAnInstanceOf('Magehack_Elasticmage_Model_Resource_Product_Collection');
    }

    function it_should_addAttributeToSelect_return_with_Magehack_Elasticmage_Model_Resource_Product_Collection()
    {
        $this->addAttributeToSelect('a')->shouldBeAnInstanceOf('Magehack_Elasticmage_Model_Resource_Product_Collection');
    }

    function it_should_addIdFilter_return_with_Magehack_Elasticmage_Model_Resource_Product_Collection()
    {
        $this->addIdFilter(123)->shouldBeAnInstanceOf('Magehack_Elasticmage_Model_Resource_Product_Collection');
    }

    function it_should_addWebsiteNamesToResult_return_with_Magehack_Elasticmage_Model_Resource_Product_Collection()
    {
        $this->addWebsiteNamesToResult()->shouldBeAnInstanceOf('Magehack_Elasticmage_Model_Resource_Product_Collection');
    }

    function it_should_addStoreFilter_return_with_Magehack_Elasticmage_Model_Resource_Product_Collection()
    {
        $this->addStoreFilter()->shouldBeAnInstanceOf('Magehack_Elasticmage_Model_Resource_Product_Collection');
    }

    function it_should_addWebsiteFilter_return_with_Magehack_Elasticmage_Model_Resource_Product_Collection()
    {
        $this->addWebsiteFilter()->shouldBeAnInstanceOf('Magehack_Elasticmage_Model_Resource_Product_Collection');
    }

    function it_should_getLimitationFilters_return_with_array()
    {
        $this->getLimitationFilters()->shouldBeArray();
    }

    function it_should_addCategoryFilter_return_with_Magehack_Elasticmage_Model_Resource_Product_Collection(\Mage_Catalog_Model_Category $category)
    {
        $this->addCategoryFilter($category)->shouldBeAnInstanceOf('Magehack_Elasticmage_Model_Resource_Product_Collection');
    }

    function it_should_joinMinimalPrice_return_with_Magehack_Elasticmage_Model_Resource_Product_Collection()
    {
        $this->joinMinimalPrice()->shouldBeAnInstanceOf('Magehack_Elasticmage_Model_Resource_Product_Collection');
    }

    function it_should_getMaxAttributeValue_return_with_Magehack_Elasticmage_Model_Resource_Product_Collection()
    {
        $this->joinMinimalPrice()->shouldBeAnInstanceOf('Magehack_Elasticmage_Model_Resource_Product_Collection');
    }

    function it_should_getMaxAttributeValue_return_with_biggest_number()
    {
        $endValue = mt_rand(1,100);
        $randomNumberArray = range(0,$endValue);
        shuffle($randomNumberArray);

        $this->getMaxAttributeValue($randomNumberArray)->shouldBe(max($randomNumberArray));
    }

    function it_should_getMaxAttributeValue_return_with_last_string_in_sort_order()
    {
        $endValue = mt_rand(1,100);
        $randomStringArray = array();
        for ($i=0; $i<$endValue; $i++) {
            $randomStringArray[] = $this->generateRandomString();
        }
        shuffle($randomStringArray);

        $this->getMaxAttributeValue($randomStringArray)->shouldBe(max($randomStringArray));
    }

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    function it_should_getMaxAttributeValue_return_with_null_in_case_of_not_existing_data()
    {
        $this->getMaxAttributeValue(null)->shouldBe(null);
    }

    function it_should_getAttributeValueCountByRange_return_array()
    {
        $this->getAttributeValueCountByRange('a', 10)->shouldBeArray();
    }

    function it_should_getAttributeValueCount_return_array()
    {
        $this->getAttributeValueCount('a')->shouldBeArray();
    }

    function it_should_getAllAttributeValues_return_array()
    {
        $this->getAllAttributeValues('a')->shouldBeArray();
    }

    function it_should_getSelectCountSql_returns_varien_db_select()
    {
        $this->getSelectCountSql()->shouldBeAnInstanceOf('Varien_Db_Select');
    }

    function it_should_getAllIds_return_array()
    {
        $this->getAllIds()->shouldBeArray();
    }

    function it_should_getProductCountSelect()
    {
        $this->getProductCountSelect()->shouldBeAnInstanceOf('Varien_Db_Select');
    }

    function it_should_addCountToCategories_return_Magehack_Elasticmage_Model_Resource_Product_Collection(\Varien_Object $categoryCollection)
    {
        $this->addCountToCategories($categoryCollection)->shouldBeAnInstanceOf('Magehack_Elasticmage_Model_Resource_Product_Collection');
    }

    function it_should_getSetIds_return_array()
    {
        $this->getSetIds()->shouldBeArray();
    }

    function it_should_getProductTypeIds_return_array()
    {
        $this->getProductTypeIds()->shouldBeArray();
    }

    function it_should_joinUrlRewrite_return_Magehack_Elasticmage_Model_Resource_Product_Collection()
    {
        $this->joinUrlRewrite()->shouldBeAnInstanceOf('Magehack_Elasticmage_Model_Resource_Product_Collection');
    }

    function it_should_addUrlRewrite_return_Magehack_Elasticmage_Model_Resource_Product_Collection()
    {
       $this->addUrlRewrite()->shouldBeAnInstanceOf('Magehack_Elasticmage_Model_Resource_Product_Collection');
    }

    function it_should_addMinimalPrice_return_Magehack_Elasticmage_Model_Resource_Product_Collection()
    {
        $this->addMinimalPrice()->shouldBeAnInstanceOf('Magehack_Elasticmage_Model_Resource_Product_Collection');
    }

    function it_should_addFinalPrice_return_Magehack_Elasticmage_Model_Resource_Product_Collection()
    {
        $this->addFinalPrice()->shouldBeAnInstanceOf('Magehack_Elasticmage_Model_Resource_Product_Collection');
    }

    function it_should_getAllIdsCache_return_array()
    {
        $this->getAllIdsCache()->shouldBeArray();
    }

    function it_should_addPriceData_return_Magehack_Elasticmage_Model_Resource_Product_Collection()
    {
        $this->addPriceData()->shouldBeAnInstanceOf('Magehack_Elasticmage_Model_Resource_Product_Collection');
    }

    function it_should_addAttributeToFilter_return_Magehack_Elasticmage_Model_Resource_Product_Collection()
    {
        $this->addAttributeToFilter('a')->shouldBeAnInstanceOf('Magehack_Elasticmage_Model_Resource_Product_Collection');
    }

    function it_should_addOptionsToResult_return_Magehack_Elasticmage_Model_Resource_Product_Collection()
    {
        $this->addOptionsToResult()->shouldBeAnInstanceOf('Magehack_Elasticmage_Model_Resource_Product_Collection');
    }

    function it_should_addAttributeToSort_return_Magehack_Elasticmage_Model_Resource_Product_Collection()
    {
        $this->addAttributeToSort('a')->shouldBeAnInstanceOf('Magehack_Elasticmage_Model_Resource_Product_Collection');
    }

    function it_should_applyFrontendPriceLimitations_return_Magehack_Elasticmage_Model_Resource_Product_Collection()
    {
        $this->applyFrontendPriceLimitations()->shouldBeAnInstanceOf('Magehack_Elasticmage_Model_Resource_Product_Collection');
    }

    function it_should_addCategoryIds_return_Magehack_Elasticmage_Model_Resource_Product_Collection()
    {
        $this->addCategoryIds()->shouldBeAnInstanceOf('Magehack_Elasticmage_Model_Resource_Product_Collection');
    }

    function it_should_addTierPriceData_return_Magehack_Elasticmage_Model_Resource_Product_Collection()
    {
        $this->addTierPriceData()->shouldBeAnInstanceOf('Magehack_Elasticmage_Model_Resource_Product_Collection');
    }

    function it_should_addPriceDataFieldFilter_return_Magehack_Elasticmage_Model_Resource_Product_Collection()
    {
        $this->addPriceDataFieldFilter('', array())->shouldBeAnInstanceOf('Magehack_Elasticmage_Model_Resource_Product_Collection');
    }

    function it_should_clear_return_Magehack_Elasticmage_Model_Resource_Product_Collection()
    {
        $this->clear()->shouldBeAnInstanceOf('Magehack_Elasticmage_Model_Resource_Product_Collection');
    }

    function it_should_setOrder_return_Magehack_Elasticmage_Model_Resource_Product_Collection()
    {
        $this->setOrder('a')->shouldBeAnInstanceOf('Magehack_Elasticmage_Model_Resource_Product_Collection');
    }
}
