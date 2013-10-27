<?php

class Magehack_Elasticmage_Model_Elasticsearch extends Varien_Object
{
    protected $_host = '192.168.33.99';
    protected $_client = null;
    protected $_params = array();
    protected $_query = array();

    public function __construct($services = array())
    {
        if (!isset($services['client'])) {
            $this->_params['hosts'] = array ($this->_host);
            $this->_client = new \Elasticsearch\Client($this->_params);
        } else {
            $this->_client = $services['client'];
        }
    }

    public function getAllIds()
    {
        $this->_query = array(
            'fields' => array(
                '_id'
            ),
            'query' => array(
                'match_all' => array()
            )
        );

        $response = $this->_sendSearchQuery();

        return array_map(function ($el) { return (int)$el['_id'];}, $response['hits']['hits'] );
    }

    public function getProductData()
    {
        $this->_query = array(
            'query' => array(
                'match_all' => array()
            )
        );

        $response = $this->_sendSearchQuery();

        $returnArray = array();
        foreach ($response['hits']['hits'] as $item) {
            $returnArray[] = $item['_source'];
        }

        return $returnArray;
    }

    public function getProductCount()
    {
        $this->_query = array('match_all'=>array());

        $response = $this->_sendCountQuery();

        return $response['count'];
    }

    protected function _applyFilters()
    {
        if (!empty($this->_query) && is_array($this->_query)) {
            $filters = $this->getFilters();

            if (!empty($filters) && isset($this->_query['query'])) {
                $this->_query['query']['filtered']['query'] = $this->_query['query'];
                unset($this->_query['query']['match_all']);
                $this->_addFilterQuery();
            }
        }

        return $this;
    }

    protected function _applyPagination()
    {
        if (isset())
    }

    protected function _addFilterQuery()
    {
        $this->_query['query']['filtered']['filter']['term'] = $this->getFilters();

        return $this;
    }

    protected function _sendSearchQuery()
    {
        $this->_applyFilters();
        $this->_applyPagination();

        $this->setFilters(null);

        $params['index'] = 'magehack';
        $params['type']  = 'product';
        $params['body']  = $this->_query;

        return $this->_client->search($params);
    }

    protected function _sendCountQuery()
    {
        $this->setFilters(null);

        $params['index'] = 'magehack';
        $params['type']  = 'product';
        $params['body']  = $this->_query;

        return $this->_client->count($params);
    }
}