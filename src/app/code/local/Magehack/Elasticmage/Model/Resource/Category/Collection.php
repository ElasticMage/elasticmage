<?php

class Magehack_Elasticmage_Model_Resource_Category_Collection extends Mage_Catalog_Model_Resource_Category_Collection
{
    public function addIdFilter($categoryIds)
    {
        return $this;
    }

    public function load($printQuery = false, $logQuery = false)
    {
        return $this;
    }

    public function loadProductCount($items, $countRegular = true, $countAnchor = true)
    {
        return $this;
    }

    public function addPathFilter($regexp)
    {
        return $this;
    }

    public function joinUrlRewrite()
    {
        return $this;
    }

    public function addPathsFilter($paths)
    {
        return $this;
    }

    public function addLevelFilter($level)
    {
        return $this;
    }

    public function addRootLevelFilter()
    {
        return $this;
    }

    public function getNewEmptyItem()
    {
        return new Varien_Object();
    }
}