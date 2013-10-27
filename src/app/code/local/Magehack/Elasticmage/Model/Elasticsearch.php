<?php

class Magehack_Elasticmage_Model_Elasticsearch extends Varien_Object
{
    protected $_host = '192.168.33.99';
    protected $_client = null;
    protected $_params = array();

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
        /*$json = '{
            "fields" : {
                "_id"
            },
            "query" : {
                "match_all" : {}
            }
        }';*/

        $json = array(
            'fields' => array(
                '_id'
            ),
            'query' => array(
                'match_all' => array()
            )
        );

        $params['index'] = 'magehack';
        $params['type']  = 'product';
        $params['body']  = $json;

        $response = $this->_client->search($params);

        return array_map(function ($el) { return (int)$el['_id'];}, $response['hits']['hits'] );
    }

    public function getProductData()
    {
        $json = array(
            'query' => array(
                'match_all' => array()
            )
        );

        $params['index'] = 'magehack';
        $params['type']  = 'product';
        $params['body']  = $json;

        $response = $this->_client->search($params);

        $returnArray = array();
        foreach ($response['hits']['hits'] as $item) {
            $returnArray[] = $item['_source'];
        }

        return $returnArray;
    }

    public function getProductCount()
    {
        $json = array('match_all'=>array());

        $params['index'] = 'magehack';
        $params['type']  = 'product';
        $params['body']  = $json;

        $response = $this->_client->count($params);

        return $response['count'];
    }
}