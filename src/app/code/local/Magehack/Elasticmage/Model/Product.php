<?php

class Magehack_Elasticmage_Model_Product extends Mage_Catalog_Model_Product
{
    /**
    * Initialize resources
    */
    protected function _construct()
    {
        $this->_init('magehack_elasticmage/product');
    }
}