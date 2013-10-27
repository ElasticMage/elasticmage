<?php

class Magehack_Elasticmage_Model_Category extends Mage_Catalog_Model_Category
{
    /**
     * Initialize resource mode
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('magehack_elasticmage/category');
    }
}