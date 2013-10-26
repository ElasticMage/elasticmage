<?php

namespace spec;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class Magehack_Elasticmage_Model_Resource_Category_CollectionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Magehack_Elasticmage_Model_Resource_Category_Collection');
    }

    function it_should_addIdFilter_returns_Magehack_Elasticmage_Model_Resource_Category_Collection()
    {
        $this->addIdFilter(array())->shouldBeAnInstanceOf('Magehack_Elasticmage_Model_Resource_Category_Collection');
    }

    function it_should_load_returns_Magehack_Elasticmage_Model_Resource_Category_Collection()
    {
        $this->load()->shouldBeAnInstanceOf('Magehack_Elasticmage_Model_Resource_Category_Collection');
    }

    function it_should_loadProductCount_returns_Magehack_Elasticmage_Model_Resource_Category_Collection()
    {
        $this->loadProductCount(array())->shouldBeAnInstanceOf('Magehack_Elasticmage_Model_Resource_Category_Collection');
    }

    function it_should_addPathFilter_returns_Magehack_Elasticmage_Model_Resource_Category_Collection()
    {
        $this->addPathFilter('')->shouldBeAnInstanceOf('Magehack_Elasticmage_Model_Resource_Category_Collection');
    }

    function it_should_joinUrlRewrite_returns_Magehack_Elasticmage_Model_Resource_Category_Collection()
    {
        $this->joinUrlRewrite()->shouldBeAnInstanceOf('Magehack_Elasticmage_Model_Resource_Category_Collection');
    }

    function it_should_addPathsFilter_returns_Magehack_Elasticmage_Model_Resource_Category_Collection()
    {
        $this->addPathsFilter('')->shouldBeAnInstanceOf('Magehack_Elasticmage_Model_Resource_Category_Collection');
    }

    function it_should_addLevelFilter_returns_Magehack_Elasticmage_Model_Resource_Category_Collection()
    {
        $this->addLevelFilter('')->shouldBeAnInstanceOf('Magehack_Elasticmage_Model_Resource_Category_Collection');
    }

    function it_should_addRootLevelFilter_returns_Magehack_Elasticmage_Model_Resource_Category_Collection()
    {
        $this->addRootLevelFilter()->shouldBeAnInstanceOf('Magehack_Elasticmage_Model_Resource_Category_Collection');
    }

    function it_should_getNewEmptyItem_returns_Varien_Object()
    {
        $this->getNewEmptyItem()->shouldBeAnInstanceOf('Varien_Object');
    }
}
