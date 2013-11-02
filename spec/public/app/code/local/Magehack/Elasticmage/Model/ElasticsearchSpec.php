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
            $this->_getSampleProductResultData()
        );
    }

    function it_requests_elastic_search_for_count_of_all_items()
    {
        $json = array(
            'match_all' => array()
        );

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
            $this->_getSampleProductResultData()
        );
    }

    function it_applies_query_filters_for_product_loading()
    {
        $json = array(
            'query' => array(
                'filtered' => array(
                    'query' => array( "match_all" => array() ),
                    'filter' => array(
                        "term" => array( "categories" => 10 )
                    )
                )
            )
        );

        $params['index'] = 'magehack';
        $params['type']  = 'product';
        $params['body']  = $json;

        $return = $this->_getProductSampleData();

        $this->_connection->search($params)->willReturn($return);


        $filters = array(
            'categories' => 10,
        );

        $this->getProductData(0, 0, $filters)->shouldReturn(
            $this->_getSampleProductResultData()
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

    private function _getSampleProductResultData()
    {
        return array(
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
        );
    }

    function it_builds_logical_or_filters()
    {
        $this->buildLogicalOrFilter(array(
            "categories" => array(1, 3),
        ))->shouldReturn(array(
                "or" => array(
                    array(
                        "term" => array(
                            "categories" => 1
                        )
                    ),
                    array(
                        "term" => array(
                            "categories" => 3
                        )
                    )
                )
            )
        );

        $this->buildLogicalOrFilter(array(
                "categories" => 1,
                "color" => "red",
        ))->shouldReturn(array(
                "or" => array(
                    array(
                        "term" => array(
                            "categories" => 1
                        )
                    ),
                    array(
                        "term" => array(
                            "color" => "red"
                        )
                    )
                )
            )
        );
    }

    function it_applies_multiple_query_filters_for_product_loading()
    {
        $json = array(
            'query' => array(
                'filtered' => array(
                    'query' => array( "match_all" => array() ),
                    'filter' => array(
                        "or" => array(
                            array(
                                "term" => array(
                                    "categories" => 1
                                )
                            ),
                            array(
                                "term" => array(
                                    "categories" => 3
                                )
                            )
                        )
                    )
                )
            )
        );

        $params['index'] = 'magehack';
        $params['type']  = 'product';
        $params['body']  = $json;

        $return = $this->_getProductSampleData();

        $this->_connection->search($params)->willReturn($return);


        $filters = array(
            'categories' => array(1, 3),
        );

        $this->getProductData(0, 0, $filters)->shouldReturn(
            $this->_getSampleProductResultData()
        );
    }

    function it_requests_elasticsearch_for_count_of_items_with_filters()
    {
        $json = array(
            'filtered' => array(
                'query' => array( "match_all" => array() ),
                'filter' => array(
                    "term" => array( "categories" => 10 )
                )
            )
        );

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

        $filters = array(
            'categories' => 10,
        );

        $this->getProductCount($filters)->shouldReturn(2);
    }

    function it_accepts_limit_parameters_for_product_data_requests_with_falsy_from_size()
    {
        // Bug with first page loading default 10 items not specified amount,
        // because we pass 0 for 'from' and code was using logical AND not OR on Size / From data.
        $json = array(
            'query' => array(
                'match_all' => array()
            ),
            'from' => 0,
            'size' => 2
        );

        $params['index'] = 'magehack';
        $params['type']  = 'product';
        $params['body']  = $json;

        $return = $this->_getProductSampleData();

        $this->_connection->search($params)->willReturn($return);


        $this->getProductData(0, 2)->shouldReturn(
            $this->_getSampleProductResultData()
        );
    }

    function it_accepts_sort_parameters_for_product_data_requests()
    {
        $json = array(
            'query' => array(
                'filtered' => array(
                    'query' => array( "match_all" => array() ),
                    'filter' => array(
                        "term" => array( "categories" => 10 )
                    )
                )
            ),
            'sort' => array(
                array(
                    "category_pos.10" => "asc"
                )
            )
        );
        $params['index'] = 'magehack';
        $params['type']  = 'product';
        $params['body']  = $json;

        $return = $this->_getProductSampleData();

        $this->_connection->search($params)->willReturn($return);

        $filters = array(
            'categories' => 10,
        );
        $sorts = array(
            array(
                "category_pos.10" => "asc"
            )
        );


        $this->getProductData(null, null, $filters, $sorts)->shouldReturn(
            $this->_getSampleProductResultData()
        );
    }
}
