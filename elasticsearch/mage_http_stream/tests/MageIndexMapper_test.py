
from ..src.MageIndexMapper import MageIndexMapper

from nose.tools import *
import unittest

import fudge

class MageIndexMapper_(unittest.TestCase):

    def test_it_builds_basic_product_index_mapping(self):
        attributes = (fudge.Fake('MageAttributes')
          .provides('getAttributesForEntity')
          .with_args(10)
          .returns([1,2])
          .provides('getLabel')
          .returns('my_attribute1')
          .next_call()
          .returns('my_attribute2')
          .provides('getMappingType')
          .returns('string')
          .next_call()
          .returns('integer')
          .provides('isSearchable')
          .returns(True)
          .next_call()
          .returns(False)
        )

        eq_(MageIndexMapper(attributes).buildProductIndexMap(),
            {
                "mappings": {
                    "product":{
                        "properties": {
                            "my_attribute1": {"type": "string", "index": "analyzed"},
                            "my_attribute2": {"type": "integer", "index": "not_analyzed"}
                        }
                    }
                }
            }
        )