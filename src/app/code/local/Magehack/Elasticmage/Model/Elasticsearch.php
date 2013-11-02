<?php

class Magehack_Elasticmage_Model_Elasticsearch extends Varien_Object
{
    protected $_host = '127.0.0.1';
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

    public function getProductData($from = null, $size = null, $filters = array(), $sorts = array())
    {
        $this->_query = array(
            'query' => array(
                'match_all' => array()
            )
        );

        if($from || $size){
            $this->_query["from"] = (int) $from;
            $this->_query["size"] = (int) $size;
        }

        if (count($filters)) {
            $this->setFilters($filters);
        }

        if(count($sorts)){
            foreach($sorts as $sort){
                $this->_query["sort"][] = $sort;
            }
        }

        $response = $this->_sendSearchQuery();

        $returnArray = array();
        foreach ($response['hits']['hits'] as $item) {
            $returnArray[] = $item['_source'];
        }

        return $returnArray;
    }

    public function getProductCount($filters = array())
    {
        $this->_query = array(
            'match_all' => array()
        );

        if ($filters) {
            $this->setFilters($filters);
        }

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
            }elseif (!empty($filters) && isset($this->_query['match_all'])){
                $this->_query['filtered']['query']['match_all'] = $this->_query['match_all'];
                unset($this->_query['match_all']);
                $this->_addFilters();
            }
        }

        return $this;
    }

    protected function _addFilterQuery()
    {
        $filters = $this->getFilters();

        if($this->_detectLogicalOrFilter($filters)){
            $this->_query['query']['filtered']['filter'] = $this->buildLogicalOrFilter($filters);
        }else{
            $this->_query['query']['filtered']['filter']['term'] = $filters;
        }

        return $this;
    }

    protected function _sendSearchQuery()
    {
        $this->_applyFilters();

        $this->setFilters(null);

        $params['index'] = 'magehack';
        $params['type']  = 'product';
        $params['body']  = $this->_query;

        return $this->_client->search($params);
    }

    protected function _sendCountQuery()
    {
        $this->_applyFilters();

        $this->setFilters(null);

        $params['index'] = 'magehack';
        $params['type']  = 'product';
        $params['body']  = $this->_query;

        return $this->_client->count($params);
    }

    public function buildLogicalOrFilter($filters){
        $res = array("or" => array());
        foreach($filters as $k => $val){
            if (is_array($val)){
               foreach($val as $v){
                   $res['or'][] = array(
                       "term" => array(
                           $k => $v
                       )
                   );
               }
            }else{
                $res['or'][] = array(
                    "term" => array(
                        $k => $val
                    )
                );
            }
        }
        return $res;
    }

    /**
     * Work out if given filters need to be built as logical OR query.
     * If key value in filter is array assume OR
     * If more than one filter assume OR
     *
     * @param $filters array
     * @return bool
     */
    protected function _detectLogicalOrFilter($filters){
        $or_query = (count($filters) > 1) ? true : false;

        if(!$or_query){
            foreach($filters as $k => $v){
                if(is_array($v)){
                    $or_query = true;
                    break;
                }
            }
        }
        return $or_query;
    }

    protected function _addFilters()
    {
        $filters = $this->getFilters();

        if($this->_detectLogicalOrFilter($filters)){
            $this->_query['filtered']['filter'] = $this->buildLogicalOrFilter($filters);
        }else{
            $this->_query['filtered']['filter']['term'] = $filters;
        }

        return $this;
    }
}