<?php

namespace spec;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class Magehack_Elasticmage_Model_ElasticsearchSpec extends ObjectBehavior
{
    private $_connection = null;

    function let(\Elasticsearch\Client $connection)
    {
        $this->_connection = $connection;
        $this->beConstructedWith(array('client' => $connection));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Magehack_Elasticmage_Model_Elasticsearch');
    }

    function it_requests_elastic_search_for_all_product_ids()
    {
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

        $return = array (
            'took' => 29,
            'timed_out' => false,
            '_shards' =>
                array(
                    'total' => 5,
                    'successful' => 5,
                    'failed' => 0,
                ),
            'hits' =>
                array(
                    'total' => 2,
                    'max_score' => 1,
                    'hits' =>
                        array(
                            0 =>
                                array(
                                    '_index' => 'magehack',
                                    '_type' => 'product',
                                    '_id' => '1',
                                    '_score' => 1,
                                ),
                            1 =>
                                array(
                                    '_index' => 'magehack',
                                    '_type' => 'product',
                                    '_id' => '2',
                                    '_score' => 1,
                                ),
                        ),
                )
        );

        $this->_connection->search($params)->willReturn($return);
        $this->getAllIds()->shouldReturn(array(1,2));
    }

    function it_requests_elastic_search_for_product_data()
    {
        $json = array(
            'query' => array(
                'match_all' => array()
            )
        );

        $params['index'] = 'magehack';
        $params['type']  = 'product';
        $params['body']  = $json;

        $return = $this->_getProductSampleData();

        $this->_connection->search($params)->willReturn($return);


        $this->getProductData()->shouldReturn(
            array(
                array(
                    "entity_id" => "1",
                    "sku" => "a1a",
                    "name" => "Product 1",
                ),
                array(
                    "entity_id" => "2",
                    "sku" => "a2a",
                    "name" => "Product 2",
                )
            )
        );
    }

    function it_requests_elastic_search_for_count_of_all_items()
    {
        $json = array('match_all'=>array());

        $params['index'] = 'magehack';
        $params['type']  = 'product';
        $params['body']  = $json;


        $return = array(
            'count' => 2,
            '_shards' => array(
                'total' => 10,
                'successful' => 10,
                'failed' => 0
            )
        );

        $this->_connection->count($params)->willReturn($return);
        $this->getProductCount()->shouldReturn(2);
    }

    function it_accepts_limit_parameters_for_product_data_requests()
    {
        $json = array(
            'query' => array(
                'match_all' => array()
            ),
            'from' => 1,
            'size' => 2
        );

        $params['index'] = 'magehack';
        $params['type']  = 'product';
        $params['body']  = $json;

        $return = $this->_getProductSampleData();

        $this->_connection->search($params)->willReturn($return);


        $this->getProductData(1, 2)->shouldReturn(
            array(
                array(
                    "entity_id" => "1",
                    "sku" => "a1a",
                    "name" => "Product 1",
                ),
                array(
                    "entity_id" => "2",
                    "sku" => "a2a",
                    "name" => "Product 2",
                )
            )
        );
    }

    private function _getProductSampleData()
    {
        return array (
            'took' => 29,
            'timed_out' => false,
            '_shards' =>
            array(
                'total' => 5,
                'successful' => 5,
                'failed' => 0,
            ),
            'hits' =>
            array(
                'total' => 2,
                'max_score' => 1,
                'hits' =>
                array(
                    0 =>
                    array(
                        '_index' => 'magehack',
                        '_type' => 'product',
                        '_id' => '1',
                        '_score' => 1,
                        '_source' => array(
                            "entity_id" => "1",
                            "sku" => "a1a",
                            "name" => "Product 1",
                        ),
                    ),
                    1 =>
                    array(
                        '_index' => 'magehack',
                        '_type' => 'product',
                        '_id' => '2',
                        '_score' => 1,
                        '_source' => array(
                            "entity_id" => "2",
                            "sku" => "a2a",
                            "name" => "Product 2",
                        ),
                    ),
                ),
            )
        );
    }
}
